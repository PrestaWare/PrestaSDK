<?php
/**
 * Prestashop Module Development Kit
 *
 * @author     Hashem Afkhami <hashemafkhami89@gmail.com>
 * @copyright  (c) 2023 - PrestaWare Team
 * @website    https://prestaware.com
 * @license    https://www.gnu.org/licenses/gpl-3.0.html [GNU General Public License]
 */
namespace PrestaSDK;

use PrestaSDK\Install\HooksInstaller;
use PrestaSDK\Install\TablesInstaller;
use PrestaSDK\Install\TabsInstaller;

class Module extends \Module
{
    public array $moduleTabs;
    public array $moduleConfigs;

    public string $configsAdminController;

    public string $pathFileSqlInstall;
    public string $pathFileSqlUninstall;

    public function __construct()
    {
        $this->context = \Context::getContext();
        $this->name = strtolower(get_class($this));
        $this->bootstrap = true;

        $this->ps_versions_compliancy = ['min' => '8.1.0', 'max' => _PS_VERSION_];

        if (method_exists($this,'initModule')) {
            $this->initModule();
        }

        parent::__construct();
    }

    /**
     * @throws \PrestaShopException
     * @throws \PrestaShopDatabaseException
     */
    public function install(): bool
    {
        if (!parent::install()) {
            return false;
        }

        if (!(new HooksInstaller($this))->installHooks()) {
            return false;
        }

        if (!(new TabsInstaller($this))->installTabs()) {
            return false;
        }

        if (!(new TablesInstaller($this))->installTables()) {
            return false;
        }

        (new Config())->updateConfigs($this->moduleConfigs);

        return true;
    }

    /**
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function uninstall(): bool
    {
        if (!parent::uninstall()) {
            return false;
        }

        if (!(new TabsInstaller($this))->uninstallTabs()) {
            return false;
        }

        if (!(new TablesInstaller($this))->uninstallTables()) {
            return false;
        }

        (new Config())->deleteConfigs($this->moduleConfigs);

        return true;
    }

    /**
     * @throws \Exception
     */
    public function getContent(): void
    {
        if (!empty($this->configsAdminController)) {
            $route = $this->get('router')->generate($this->configsAdminController);
            \Tools::redirectAdmin($route);
        }
    }

    public function getPsBaseUrl(): bool|string
    {
        $auto_secure_mode = \Configuration::get('PS_SSL_ENABLED');
        return \Context::getContext()->shop->getBaseURL($auto_secure_mode);
    }

    /**
     * Get This Module Url
     *
     * @param null $extraPath
     *
     * @return string
     */
    public function getModuleUrl($extraPath = null): string
    {
        $url = $this->getPsBaseUrl() . 'modules/' . $this->name . '/';
        if (!empty($extraPath)) {
            $url .= ltrim($extraPath, '/');
        }
        return $url;
    }

    /**
     * getModuleAdminLink
     */
    public function getModuleAdminLink($controller, $params = [], $withToken = true): string
    {
        return $this->context->link->getAdminLink($controller, $withToken, $params);
    }

}