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

class Config
{
    public static function updateConfigs(array $configs, $updateBySubmitValue = false, $enabledHtml = false): void
    {
        foreach ($configs as $keyConfig => $valueConfig) {
            if ($updateBySubmitValue) {
                if (!is_int($keyConfig)) {
                    \Configuration::updateValue($keyConfig, \Tools::getValue($keyConfig), $enabledHtml);
                } else {
                    \Configuration::updateValue($valueConfig, \Tools::getValue($valueConfig) , $enabledHtml);
                }
            } else {
                // update Config By Key Value Array!
                if (!is_int($keyConfig)) {
                    \Configuration::updateValue($keyConfig, $valueConfig, $enabledHtml);
                }
            }
        }
    }

    public static function deleteConfigs(array $configs): void
    {
        foreach ($configs as $keyConfig => $valueConfig) {
            if (!is_int($keyConfig)) {
                \Configuration::deleteByName($keyConfig);
            } else {
                \Configuration::deleteByName($valueConfig);
            }
        }
    }
}