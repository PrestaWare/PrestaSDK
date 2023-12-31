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

namespace PrestaSDK\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Response;

abstract class AdminController extends FrameworkBundleAdminController implements ControllerInterface
{
    /** @var \Module */
    protected \Module $module;
    protected array $sdkVars = [];
    protected array $sdkPositions = [];

    abstract public static function getModuleName(): string;

    public function __construct()
    {
        parent::__construct();

        $this->module = \Module::getInstanceByName($this->getModuleName());
    }

    public function initSDKPanel(): void
    {
        /* set default sdk twig vars */
        $vars = [
            '_positions' => $this->releasePositions(),
            'pathAssets' => $this->getPathAssets(),
            'pathViewsSDK' => $this->getPathViewsSDK(),

            'module_logo_src' => $this->module->getModuleUrl() . 'logo.png',
            'module_displayName' => $this->module->displayName,
            'module_name' => $this->module->name,
            'module_version' => $this->module->version,
            'module_content' => '',

            'menu_items' => $this->getMenuItems(),
        ];

        $this->sdkVars = array_merge($vars, $this->sdkVars);
    }

    public function appendToPanel($posName, $value, $order = 10, $key = null): void
    {
        if (!is_string($value) or empty($value)) {
            return;
        }

        $newContent = [
            'value' => $value,
            'order' => is_int($order) ? $order : 10,
            'key' => !empty($key) ? (string) $key : uniqid(),
        ];

        if (!isset($this->sdkPositions[$posName]) || !is_array($this->sdkPositions[$posName])) {
            $this->sdkPositions[$posName] = [];
        }

        foreach ($this->sdkPositions[$posName] as $oldContentKey => $oldContent) {
            if ($oldContent['key'] == $newContent['key']) {
                unset($this->sdkPositions[$posName][$oldContentKey]);
                break;
            }
        }

        $this->sdkPositions[$posName][] = $newContent;
    }

    protected function getMenuItems(): array
    {
        return [];
    }

    private function getPathSDK(): string
    {
        return 'vendor/prestaware/prestasdk/src';
    }

    private function getPathViewsSDK(): string
    {
        $basePathLayout = '@Modules/' . $this->getModuleName() . '/';
        return $basePathLayout . $this->getPathSDK() . '/Resources/views';
    }

    private function getPathAssets(): string|bool
    {
        if (!is_dir($this->module->getLocalPath().'views/prestasdk')) {
            return false;
        }

        return 'modules/' . $this->getModuleName() . '/views/prestasdk';
    }

    private function releasePositions(): array
    {
        $mergedArray = [];

        foreach ($this->sdkPositions as $posName => &$allAppended) {
            usort($allAppended, function ($p1, $p2) {
                return $p1['order'] - $p2['order'];
            });

            $posMerge = '';
            foreach ($allAppended as $contentArray) {
                $posMerge .= $contentArray['value'];
            }

            $mergedArray[$posName] = $posMerge;
        }

        return $mergedArray;
    }

    protected function renderLayout(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->sdkVars['viewContent'] = $view;

        $this->initSDKPanel();

        $parameters = array_merge($parameters, $this->sdkVars);

        return $this->render($this->getPathViewsSDK() . '/layout.html.twig', $parameters, $response);
    }

    protected function renderFormConfigure($createView, array $parameters = [], Response $response = null): Response
    {
        $this->sdkVars['viewContent'] = $this->getPathViewsSDK() . '/Blocks/form.html.twig';

        $this->initSDKPanel();

        $parameters = array_merge($parameters, $this->sdkVars, [
            'configureForm' => $createView,
        ]);

        return $this->render($this->getPathViewsSDK() . '/layout.html.twig', $parameters, $response);
    }
}