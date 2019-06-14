
<?php
/**
 * 2007-2018 Frédéric BENOIST
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *  @author    Frédéric BENOIST
 *  @copyright 2013-2018 Frédéric BENOIST <https://www.fbenoist.com/>
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
     exit;
}

class FbSample_AddColumnInProdList extends Module
{
    public function __construct()
    {
        $this->name = 'fbsample_addcolumninprodlist';
        $this->author = 'Frédéric BENOIST';
        $this->version = '1.0.0';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->tab = 'others';
        parent::__construct();

        $this->displayName = $this->l('Add column in product list');
        $this->ps_versions_compliancy = array(
            'min' => '1.7.5',
            'max' => _PS_VERSION_
        );
        $this->description = $this->l(
            'Sample PrestaShop 1.7 module, add column in product list.'
        );
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayAdminCatalogProductHeader')
            || !$this->registerHook('displayAdminCatalogProductFilter')
            || !$this->registerHook('displayAdminCatalogListingProductFields')
            || !$this->registerHook('actionAdminProductsListingFieldsModifier')
        ) {
            return false;
        }
        return true;
    }

    public function hookDisplayAdminCatalogProductHeader($params)
    {
        return $this->display(
            __FILE__,
            'views/templates/hook/product_list_header.tpl'
        );
    }

    public function hookDisplayAdminCatalogProductFilter($params)
    {
        $manufacturers = Manufacturer::getManufacturers();
        $this->context->smarty->assign([
            'filter_column_manufacturer' => Tools::getValue('filter_column_manufacturer', ''),
            'manufacturers' => $manufacturers
        ]);
        return $this->display(
            __FILE__,
            'views/templates/hook/product_list_filter.tpl'
        );
    }

    public function hookDisplayAdminCatalogListingProductFields($params)
    {
        $this->context->smarty->assign(
            'product',
            $params['product']
        );
        return $this->display(
            __FILE__,
            'views/templates/hook/product_list_fields.tpl'
        );
    }

    public function hookActionAdminProductsListingFieldsModifier($params)
    {
        // Manufacturer
        $params['sql_select']['manufacturer'] = [
            'table' => 'm',
            'field' => 'name',
            'filtering' => \PrestaShop\PrestaShop\Adapter\Admin\AbstractAdminQueryBuilder::FILTERING_LIKE_BOTH
            ];
        
        $params['sql_table']['m'] = [
            'table' => 'manufacturer',
            'join' => 'LEFT JOIN',
            'on' => 'p.`id_manufacturer` = m.`id_manufacturer`',
        ];
        
        $manufacturerFilter = Tools::getValue('filter_column_manufacturer', false);
        if ($manufacturerFilter && $manufacturerFilter !=  '') {
            $params['sql_where'][] .= " p.id_manufacturer = ".$manufacturerFilter;
        }
    }
}
