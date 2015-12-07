<?php

if (!function_exists('geoip_country_code_by_addr')) {
    require __DIR__ . '/GeoIp/src/geoip.inc';
}

//Include geoip functions when geoip extension is not installed
if (!function_exists('geoip_database_info')) {
    require_once __DIR__ . '/GeoIp/src/geoip.functions.php';
}

class Shopware_Plugins_Frontend_CheckLocation_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        try {
            $this->registerController();
            Shopware()->Front()->Response()->setCookie('shopLang', 'en', 0);

            return array(
                'success' => true,
                'invalidateCache' => array('backend')
            );
        } catch (Exception $e) {

            return array('success' => false, 'message' => $e->getMessage());
        }

        return true;
    }

    public function uninstall()
    {

        return true;
    }

    public function getCapabilities()
    {
        return array(
            'install' => true,
            'enable' => true,
            'update' => true
        );
    }

    public function afterInit()
    {
        $this->registerCustomModels();
    }

    public function registerController()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Index', 'onPostDispatchIndexController'
        );
    }

    public function onPostDispatchIndexController(Enlight_Event_EventArgs $arguments)
    {
        $controller = $arguments->getSubject();
        $view = $controller->View();
        $view->addTemplateDir($this->Path() . 'Views/');

        $shopLang = Shopware()->Front()->Request()->getCookie('shopLang');
//        $ip = $_SERVER['REMOTE_ADDR'];
        $ip = '195.149.248.130'; //BG
//        $ip = '194.50.69.124'; //DE
//        $ip = '211.156.198.82'; //CN
        $gi = geoip_open(__DIR__ . '/GeoIp/db/GeoIP.dat', GEOIP_STANDARD);
        $countryCode = geoip_country_code_by_addr($gi, $ip);
        geoip_close($gi);

        if ($shopLang != strtolower($countryCode)) {

            $shopLang = strtolower($countryCode);
            Shopware()->Front()->Response()->setCookie('shopLang', $shopLang, 0);

            $builder = Shopware()->Container()->get('dbal_connection')->createQueryBuilder();
            $shopId = $builder->select('scs.id')
                ->from('s_core_locales', 'scl')
                ->innerJoin('scl', 's_core_shops', 'scs', 'scl.id = scs.locale_id')
                ->where('scl.locale LIKE ?')
                ->setParameter(0, $shopLang . '%')
                ->execute()
                ->fetch();

            if ($shopId) {
                $view->extendsTemplate('frontend/index/change_shop.tpl');
                $view->assign(array('shopId' => $shopId['id']));
            }
        }

    }
}