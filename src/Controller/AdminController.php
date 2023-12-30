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
    
    private function getPathSDK(): string
    {
        $currentPath = strtr(__DIR__, ['\\' => '/']);
        $modulePath = strtr($this->module->getLocalPath(), ['\\' => '/']);
        return strtr($currentPath, [
            '/Controller' => '',
            $modulePath => '',
        ]);
    }

    private function getPathLayout(): string
    {
        $basePathLayout = '@Modules/' . $this->getModuleName() . '/';
        return $basePathLayout . $this->getPathSDK() . '/Views/layout.html.twig';
    }

    protected function renderLayout(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->sdkVars['viewContent'] = $view;

        $this->initSDKPanel();
        
        $parameters = array_merge($parameters, $this->sdkVars);

        return $this->render($this->getPathLayout(), $parameters, $response);
    }

    public function initSDKPanel(): void
    {
        /* set default sdk twig vars */
        $vars = [
            '_positions' => $this->releasePositions(),
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
}
