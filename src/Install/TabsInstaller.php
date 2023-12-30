<?php
/**
 * Prestashop Module Development Kit
 *
 * @author     Hashem Afkhami <hashemafkhami89@gmail.com>
 * @copyright  (c) 2023 - PrestaWare Team
 * @website    https://prestaware.com
 * @license    https://www.gnu.org/licenses/gpl-3.0.html [GNU General Public License]
 */
declare(strict_types=1);

namespace PrestaSDK\Install;

class TabsInstaller
{
    private \Module $module;

    public function __construct(\Module $module)
    {
        $this->module = $module;
    }

    /**
     * @throws \PrestaShopException
     * @throws \PrestaShopDatabaseException
     */
    public function installTabs(): bool
    {
        if (empty($this->module->moduleTabs)) {
            return true;
        }

        $result = true;
        foreach ($this->module->moduleTabs as $tabItem) {
            $tabId = \Tab::getIdFromClassName($tabItem['class_name']);

            if (!$tabId) {
                $tabId = null;
            }

            $tab = new \Tab($tabId);
            $tab->id_parent = $tabItem['parent'] ? \Tab::getIdFromClassName($tabItem['parent']) : 0;
            $tab->class_name = $tabItem['class_name'];
            $tab->route_name = $tabItem['route_name'];
            $tab->icon = $tabItem['icon'];
            $tab->active = $tabItem['active'];
            $tab->enabled = $tabItem['enabled'];
            $tab->module = $this->module->name;
            $tab->name = [];

            $names = $tabItem['name'];

            $languages = \Language::getLanguages(false);
            foreach ($languages as $language) {
                $tab->name[(int) $language['id_lang']] = $names[$language['iso_code']] ?? $names['en'];
            }

            $result = $result && $tab->save();

            $tab->updatePosition(false, $tabItem['position']);
        }

        return $result;
    }

    /**
     * @throws \PrestaShopException
     * @throws \PrestaShopDatabaseException
     */
    public function uninstallTabs(): bool
    {
        if (empty($this->module->moduleTabs)) {
            return true;
        }

        $result = true;
        foreach ($this->module->moduleTabs as $tabItem) {
            $id_tab = (int) \Tab::getIdFromClassName($tabItem['class_name']);

            // todo :: check parent delete ?

            $tab = new \Tab($id_tab);
            if (\Validate::isLoadedObject($tab) && $tab->module === $this->module->name) {
                $result = $result && $tab->delete();
            }
        }

        return $result;
    }
}
