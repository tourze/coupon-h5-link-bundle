<?php

declare(strict_types=1);

namespace Tourze\CouponH5LinkBundle\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tourze\CouponCoreBundle\Entity\Coupon;
use Tourze\CouponH5LinkBundle\Entity\H5Link;

/**
 * H5外链CRUD控制器
 *
 * 管理优惠券H5外链的后台操作界面，包括：
 * - H5外链的创建、编辑和查看
 * - 与优惠券的一对一关联管理
 * - URL有效性验证和显示
 * - 跟踪审计信息展示
 * - 时间戳和用户追踪展示
 *
 * @author Claude AI <noreply@anthropic.com>
 */
#[AdminCrud(routePath: '/coupon/h5-link', routeName: 'coupon_h5_link')]
final class H5LinkCrudController extends AbstractCrudController
{
    public function __construct()
    {
    }

    public static function getEntityFqcn(): string
    {
        return H5Link::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('H5外链')
            ->setEntityLabelInPlural('H5外链列表')
            ->setPageTitle('index', 'H5外链管理')
            ->setPageTitle('new', '新增H5外链')
            ->setPageTitle('edit', '编辑H5外链')
            ->setPageTitle('detail', fn (H5Link $h5Link) => sprintf('H5外链 <strong>%s</strong> 详情', $h5Link->getId()))
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['url'])
            ->setHelp('index', '管理优惠券H5外链，每个优惠券可以关联一个H5外链地址')
            ->setPaginatorPageSize(50)
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->setMaxLength(9999)
            ->hideOnForm()
        ;

        yield AssociationField::new('coupon', '关联优惠券')
            ->setRequired(true)
            ->setColumns(6)
            ->formatValue(function ($value) {
                if (!$value instanceof Coupon) {
                    return '<span class="text-muted">未关联优惠券</span>';
                }

                return sprintf('%s (%s)', $value->getName() ?? 'N/A', $value->getSn() ?? 'N/A');
            })
            ->setHelp('选择要关联的优惠券，每个优惠券只能关联一个H5外链')
        ;

        yield UrlField::new('url', 'H5链接')
            ->setRequired(true)
            ->setColumns(12)
            ->setHelp('请输入有效的H5页面URL地址，用于优惠券的展示或领取页面')
        ;

        yield TextField::new('createdBy', '创建人')
            ->hideOnForm()
            ->setColumns(3)
        ;

        yield TextField::new('updatedBy', '更新人')
            ->hideOnForm()
            ->hideOnIndex()
            ->setColumns(3)
        ;

        yield TextField::new('createIp', '创建IP')
            ->hideOnForm()
            ->hideOnIndex()
            ->setColumns(3)
        ;

        yield TextField::new('updateIp', '更新IP')
            ->hideOnForm()
            ->hideOnIndex()
            ->setColumns(3)
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->setColumns(4)
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
            ->hideOnIndex()
            ->setColumns(4)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('coupon', '关联优惠券'))
            ->add(TextFilter::new('url', 'H5链接'))
            ->add(TextFilter::new('createdBy', '创建人'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        // 添加预览H5链接的自定义操作
        $previewAction = Action::new('preview', '预览', 'fas fa-eye')
            ->linkToCrudAction('preview')
            ->setHtmlAttributes([
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
            ])
            ->setCssClass('btn btn-outline-info')
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $previewAction)
            ->add(Crud::PAGE_DETAIL, $previewAction)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN')
            ->setPermission('preview', 'ROLE_USER')
        ;
    }

    /**
     * 预览H5链接
     */
    #[AdminAction(routeName: 'admin_h5_link_preview', routePath: '/preview')]
    public function preview(AdminContext $context): Response
    {
        $h5Link = $context->getEntity()->getInstance();
        assert($h5Link instanceof H5Link);

        $url = $h5Link->getUrl();
        if (null === $url || '' === $url) {
            $this->addFlash('warning', 'H5链接地址为空，无法预览');

            return $this->redirectToRoute('admin');
        }

        // 直接重定向到H5链接地址
        return new RedirectResponse($url);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $queryBuilder
            ->select('entity, coupon')
            ->leftJoin('entity.coupon', 'coupon')
            ->orderBy('entity.createTime', 'DESC')
        ;
    }
}
