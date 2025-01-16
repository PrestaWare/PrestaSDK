<?php
/**
 * Prestashop Module Development Kit
 *
 * @author     Hashem Afkhami <hashemafkhami89@gmail.com>
 * @copyright  (c) 2025 - PrestaWare Team
 * @website    https://prestaware.com
 * @license    https://www.gnu.org/licenses/gpl-3.0.html [GNU General Public License]
 */
namespace PrestaSDK\Utility;

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

    public static function setMultipleValues(array $keys, $configName = null, $nullValues = false)
    {
        $values = [];

        foreach ($keys as $key) {
            $value = \Tools::getValue($key);

            if ($value) {
                $values[$key] = $value;
            } elseif (!$value && $nullValues) {
                $values[$key] = null;
            }
        }

        if (!empty($configName)) {
            \Configuration::updateValue($configName, json_encode($values), true);
        }

        return $values;
    }

    public function validateAndSaveConfig($configName, $validationMethod, $required = false)
    {
        $value = \Tools::getValue($configName);

        if (empty($value) && !$required) {
            return \Configuration::updateValue($configName, null);
        }

        if (method_exists('Validate', $validationMethod) && \Validate::$validationMethod($value)) {
            return \Configuration::updateValue($configName, $value);
        }

        return false;
    }
}