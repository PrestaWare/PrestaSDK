# PrestaSDK Documentation
## Chapter 1: Introduction & Quick Start
This chapter provides a general overview of PrestaSDK, how to install it, and the initial setup. By the end of this chapter, you will have created your first module using this SDK.
### 1.1. What is PrestaSDK?
PrestaSDK is a Software Development Kit (SDK) for building PrestaShop modules, designed to increase speed, standardize development, and improve code maintainability. Module development in PrestaShop can sometimes involve repetitive code and complex processes for common tasks like installation, configuration management, or creating an admin panel. PrestaSDK automates and simplifies these processes by providing a structured framework and a set of helper tools.
#### Main Goals:
- Rapid Development: By automating repetitive tasks such as registering hooks, installing admin tabs, and managing database tables, it allows you to focus on your module's core logic.
- Standardized Structure: All modules developed with this SDK follow a consistent architecture, making it easier to learn, develop, and collaborate on projects.
- High Maintainability: The code you write will be more readable, cleaner, and better organized, which simplifies future maintenance and updates.
#### Key Advantages:
- Automatic Installers: The Installer components easily handle the installation of tabs, hooks, and database tables.
- Ready-Made Admin Panel: Using AdminController and PanelCore, you get a beautiful and functional admin panel structure with a sidebar and a templating system.
- Powerful Helper Classes: Tools are provided for working with forms (HelperForm), configurations (Config), and general methods (HelperMethods).
- Automatic Asset Management: CSS and JavaScript files are automatically copied to the correct path and versioned to prevent browser caching issues.
### 1.2. Installation and Setup
To use PrestaSDK in your module, you just need to add it as a dependency via Composer.
#### Prerequisites:
- PHP: Version 7.4 or higher
- PrestaShop: Version 1.7.8 or higher (Recommended: 8.1.0+)
- Composer: Installed on your system
#### Installation Steps:

- Navigate to your module's root directory.
- Run the following command in your terminal to add the SDK to your project:

```bash
composer require prestaware/prestasdk
```

This command will create a vendor directory and an autoload.php file in your module's root.

- In your main module file (e.g., mymodule.php), include the autoload.php file to make the SDK classes available:

```php
// mymodule/mymodule.php
if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
    require_once dirname(__FILE__).'/vendor/autoload.php';
}
```
### 1.3. Creating Your First Module (Hello World)
In this section, we'll create a simple module that demonstrates the basics of working with PrestaSDK.
#### 1. Initial File Structure

First, create the following folder structure for your module:

```
/modules
  /myhelloworld
    - myhelloworld.php   (Main module file)
    - composer.json
    - logo.png
    - config.xml
```
#### 2. Creating the Main Module Class

Create the content of myhelloworld.php as follows. The most important point is that your main class must extend PrestaSDK\V040\PrestaSDKModule.

```php
<?php
// myhelloworld/myhelloworld.php

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
    require_once dirname(__FILE__).'/vendor/autoload.php';
}

use PrestaSDK\V040\PrestaSDKModule;

class MyHelloWorld extends PrestaSDKModule
{
    public function __construct()
    {
        // All initial settings should be placed in the initModule method
        // This method is called by the parent constructor
        parent::__construct();
    }

    /**
     * The main and initial settings of the module are defined in this method.
     */
    public function initModule()
    {
        $this->name = 'myhelloworld';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;
        $this->bootstrap = true;

        $this->displayName = $this->l('My Hello World Module');
        $this->description = $this->l('A simple module created with PrestaSDK.');

        $this->ps_versions_compliancy = ['min' => '1.7.8', 'max' => _PS_VERSION_];
    }

    /**
     * Install method - the SDK does the rest
     */
    public function install()
    {
        return parent::install();
    }

    /**
     * Uninstall method - the SDK does the rest
     */
    public function uninstall()
    {
        return parent::uninstall();
    }
}
```
#### 3. Introducing the initModule Method
As you can see in the code above, all the main module properties like name, version, displayName, etc., are defined inside the initModule() method.
This method is automatically called by the PrestaSDKModule's constructor (__construct). This separates the module's initialization logic from the main constructor, resulting in cleaner and more organized code.
With just these few lines of code, you have created a standard module. You can now install it. In the following chapters, we will learn how to add controllers, settings, admin tabs, and more.
## Chapter 2: Core Concepts
This chapter delves deeper into the main components of PrestaSDK. Understanding these concepts will help you leverage the full potential of the SDK to build complex and powerful modules.
### 2.1. The PrestaSDKModule Class
This class is the starting point for any SDK-based module. It extends the standard PrestaShop Module class, adding a wealth of functionalities. By defining a few properties in your module class, you can delegate the entire installation and configuration process to the SDK.
#### Key Properties:

- `$moduleTabs` (array): Defines all the admin tabs (menus) that your module needs. TabsInstaller automatically creates these tabs during module installation.
- `$moduleConfigs` (array): A list of all configuration keys (Configuration) your module uses. Their default values are defined in this array and automatically saved to the database upon installation.
- `$configsAdminController` (string|array): Specifies the name of the main admin controller for the module. This automatically links the "Configure" button in the module list to this controller's page.
- `$moduleGrandParentTab` (string): If you want your module's tabs to be under another main tab (e.g., "Sell"), enter the class name of that tab in this property.
- `$pathFileSqlInstall` / `$pathFileSqlUninstall` (string): Specifies the path to the SQL files for creating or dropping database tables during installation/uninstallation. By default, the SDK looks for install.sql and uninstall.sql in the module's sql/ directory.
#### Module Lifecycle
The install() and uninstall() methods in PrestaSDKModule are overridden to automatically execute the necessary processes. When you call parent::install(), the SDK performs the following tasks in order:

- Install Tabs: Based on the `$moduleTabs` array.
- Register Hooks: By scanning for all hook... methods in your module class.
- Execute SQL: Using the file defined in `$pathFileSqlInstall`.
- Save Configurations: Initial values from `$moduleConfigs` are stored in the database.
- Publish Assets: The SDK's CSS/JS files are copied into your module's views directory.

Therefore, you no longer need to write repetitive logic for these tasks.
### 2.2. Admin Panel Structure
PrestaSDK provides a powerful system for building modern, multi-section admin panels.
#### AdminController and PanelCore
To create a page in the admin area, you simply need to create a controller that extends PrestaSDK\V040\Controller\AdminController. This base class automatically uses a Trait called PanelCore, which contains all the logic for rendering the panel, managing sections, and handling templating.
#### The Concept of Sections
A key feature of AdminController is managing pages through "sections". Instead of creating multiple controllers for different pages (like settings, lists, adding new items), you can implement all the logic in a single controller using methods with the pattern section<Name>.
- Example: If your URL is ...&section=settings, the SDK will automatically call the sectionSettings() method in your controller and display its content.
- Default Section: If the section parameter is not present in the URL, the sectionIndex() method will be executed.

This approach keeps your code much cleaner and more organized.
#### Layout System and Positions
The SDK's admin panel consists of a main layout.tpl file with various positions like Sidebar, Header, TopContent, and Footer. From within your controller, you can inject HTML content into any of these positions.
For example, to add a sidebar menu to the panel, you just need to render the menu's HTML and add it to the Sidebar position with the following method:

```php
$sidebarHtml = $this->renderPanelTemplate('_partials/sidebar.tpl', $vars);
$this->appendToPanel('Sidebar', $sidebarHtml);
```

This feature allows you to create a unified yet fully customizable user interface.
### 2.3. Version Management, Namespace, and Factory
#### Versioned Namespace
A significant challenge in the PrestaShop ecosystem is the potential for conflicts between different modules. If two modules use a shared library like PrestaSDK but require different versions of it, you might encounter fatal errors due to class or function re-declarations.
To solve this, all PrestaSDK classes are placed within a versioned namespace. For example, in version 0.4.0, all classes reside under PrestaSDK\V040:

```php
namespace PrestaSDK\V040;

class PrestaSDKModule extends \Module
{
    // ...
}
```

This structure ensures that if a future version 0.5.0 is released with the namespace PrestaSDK\V050, its code will not conflict with older versions.
#### Developer's Responsibility
When using the SDK, it is your responsibility as a developer to use the correct namespace corresponding to the SDK version installed in your module.

```php
// Correct usage for version 0.4.0
use PrestaSDK\V040\PrestaSDKModule;
use PrestaSDK\V040\Controller\AdminController;

class MyModule extends PrestaSDKModule 
{
    //...
}
```

If you decide to upgrade the SDK version in your module in the future, you must manually update your use statements to the new version (e.g., PrestaSDK\V050\...).
#### PrestaSDKFactory
The PrestaSDKFactory class is a helper tool to simplify the process of creating instances of SDK classes within the same version. Instead of directly calling new \PrestaSDK\V040\Utility\Config(...), you can use the Factory for more readable code.

```php
use PrestaSDK\V040\PrestaSDKFactory;

// This Factory creates an instance of the Config class from the V040 namespace
$config = PrestaSDKFactory::getUtility('Config', [$this->moduleConfigs]);
```

This pattern enhances code readability, but the responsibility of choosing the correct versioned namespace remains with you.
## Chapter 3: Module Installation Process
This chapter shows you how to fully automate your module's installation process using the Installer classes in PrestaSDK. You just need to define a few properties in your main module class, and the SDK will handle the rest.
### 3.1. Installing Database Tables (TablesInstaller)
To create the necessary tables for your module in the database, simply place your SQL files in the sql/ directory at the root of your module.

- sql/install.sql: SQL commands for creating tables.
- sql/uninstall.sql: SQL commands for dropping tables upon module uninstallation.

TablesInstaller automatically finds and executes these files. It intelligently replaces placeholders like _DB_PREFIX_ and _MYSQL_ENGINE_ with the correct values.

Example (install.sql):

```sql
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_wabulkupdate_file` (
    `id_wabulkupdate_file` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `file_name` VARCHAR(255) NOT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 0,
    `date_add` DATETIME NOT NULL,
    `date_upd` DATETIME NOT NULL,
    PRIMARY KEY (`id_wabulkupdate_file`)
) ENGINE=_MYSQL_ENGINE_ DEFAULT CHARSET=utf8;
```

Note: If you want to place your SQL files in a different directory, you can specify their full paths in the `$pathFileSqlInstall` and `$pathFileSqlUninstall` properties of your main module class.
### 3.2. Adding Admin Tabs (TabsInstaller)
To create menus for your module in the admin panel, define their structure in the `$moduleTabs` property of your main module class.

The structure of this array is `['AdminClassName' => [options]]`.

- **AdminClassName**: The name of your controller class (without the Controller suffix).
- **options**: An array of tab settings:
  - `title`: The title displayed in the menu.
  - `parent_class_name`: The class name of the parent controller. If this is a submenu item, provide the parent's class name here. To create a top-level tab, use a custom name and also define it in the `$moduleGrandParentTab` property.
  - `icon`: (Optional) The class name for the icon (e.g., icon-cogs).
  - `visible`: (Optional) Set to false to hide the tab.
Example (from the wabulkupdate module):

In this example, a main tab AdminBulkUpdate is defined first, and then other tabs are placed as its children.

```php
// wabulkupdate.php
public function initModule()
{
    // ...
    $this->moduleGrandParentTab = 'AdminBulkUpdate'; // Main tab class name
    
    $this->moduleTabs = [
        // Main Tab
        'AdminBulkUpdate' => [
            'title' => $this->l('Bulk Update'),
            'parent_class_name' => '', // No parent, so it's a root tab
            'icon' => 'icon-cloud-upload',
        ],
        // Child Tabs
        'AdminBulkUpdatePanel' => [
            'title' => $this->l('Panel'),
            'parent_class_name' => $this->moduleGrandParentTab, // Child of AdminBulkUpdate
            'icon' => 'icon-dashboard',
        ],
        'AdminBulkUpdateFiles' => [
            'title' => $this->l('Files'),
            'parent_class_name' => $this->moduleGrandParentTab,
            'icon' => 'icon-file-text',
        ],
        'AdminBulkUpdateLogs' => [
            'title' => $this->l('Logs'),
            'parent_class_name' => $this->moduleGrandParentTab,
            'icon' => 'icon-list-ul',
        ]
    ];
}
```
### 3.3. Automatic Hook Registration (HooksInstaller)
One of the biggest advantages of PrestaSDK is that you don't need to register hooks manually. The HooksInstaller class uses PHP's Reflection API to inspect your main module class, finds all public methods that start with the hook prefix, and automatically registers the corresponding hooks for you.
For example, to hook into actionProductUpdate, you just need to create the following method in your main module class:

```php
// mymodule.php
class MyModule extends PrestaSDKModule
{
    // ...
    
    public function hookActionProductUpdate($params)
    {
        // Your logic here...
        $product = $params['product'];
        // Do something with the updated product.
    }

    public function hookDisplayHeader($params)
    {
        // Your logic for header...
    }
}
```

During installation, the actionProductUpdate and displayHeader hooks will be automatically registered for your module.
### 3.4. Managing Initial Configurations (Config)
To define default values for your module's settings, use the `$moduleConfigs` property. This property is a key => value array where key is the variable name in the ps_configuration table and value is its default value.

Example:

```php
// mymodule.php
public function initModule()
{
    // ...
    $this->perfixConfigs = 'MYMODULE'; // (Optional) A prefix to avoid name conflicts
    
    $this->moduleConfigs = [
        'MYMODULE_ENABLE_FEATURE' => 1,
        'MYMODULE_API_KEY' => 'default_api_key',
        'MYMODULE_ITEMS_PER_PAGE' => 10,
    ];
}
```

During installation, the Config class will automatically save all these values to the database. To read these values anywhere in your module, you can use the helper method:

```php
$apiKey = $this->config->getConfig('API_KEY'); 
// Output: 'default_api_key'
// No need to include the prefix
```
## Chapter 4: Admin Panel Development
This chapter guides you through the process of building a complete admin panel using PrestaSDK's tools. From creating controllers and menus to managing forms and lists, everything is covered here.
### 4.1. Creating an Admin Controller
The first step to creating a page in the admin area is to build a controller class. This class must extend PrestaSDK\V040\Controller\AdminController. This base class provides all the functionalities of PrestaShop's ModuleAdminController along with the features of PanelCore.
Steps:

- Create a PHP file in your module's controllers/admin/ directory. The filename should match the controller class name (e.g., AdminMyPanelController.php).
- Define your class by extending AdminController.

Basic Example:

```php
// controllers/admin/AdminMyPanelController.php
use PrestaSDK\V040\Controller\AdminController;

class AdminMyPanelController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // Initial controller settings go here
    }

    /**
     * This method replaces getContent() and is the main entry point for rendering the panel.
     */
    public function initContent()
    {
        // Instead of calling parent::initContent() directly, we use initAdminPanel()
        // This method manages and renders all panel components (sidebar, content, etc.).
        $this->content = $this->initAdminPanel();
    }
}
```

After defining the controller and linking it to a tab (as explained in Chapter 3), PrestaShop will automatically display it in the admin menu.
### 4.2. Sidebar Menu
To create a navigation menu on the side of your panel, simply implement the getmenuItems() method in your controller class. This method should return an array with a specific structure. PanelCore will automatically render this menu and place it in the Sidebar position.
Array Structure:

The array should contain groups of menu items. Each item can have its own submenu.

```php
// controllers/admin/AdminBulkUpdatePanelController.php
public function getmenuItems()
{
    $menuItems = [
        'main_group' => [ // Group key
            'panel' => [ // Item key
                'title' => $this->l('Panel'),
                'link' => $this->module->getModuleAdminLink('AdminBulkUpdatePanel'),
                'icon' => 'icon-dashboard',
            ],
            'files' => [
                'title' => $this->l('Files'),
                'link' => $this->module->getModuleAdminLink('AdminBulkUpdateFiles'),
                'icon' => 'icon-file-text',
            ],
            'logs' => [
                'title' => $this->l('Logs'),
                'link' => $this->module->getModuleAdminLink('AdminBulkUpdateLogs'),
                'icon' => 'icon-list-ul',
            ],
        ],
    ];

    return $menuItems;
}
```

Notes:

- `$this->module->getModuleAdminLink(...)` is the best way to generate internal panel links.
- The SDK automatically highlights the active menu item based on the current controller.
### 4.3. Managing Pages with Sections
As mentioned in the previous chapter, you can manage the logic for different pages within a single controller using methods prefixed with section.
Example:

Suppose you have a controller for managing custom products. You can handle the list, add, and edit pages as follows:

```php
class AdminCustomProductsController extends AdminController
{
    // ...
    
    // This method runs for a URL without a section parameter (the default page)
    public function sectionIndex()
    {
        // Logic to display the product list
        return $this->renderModuleTemplate('admin/list_products.tpl');
    }

    // This method runs for &section=edit
    public function sectionEdit()
    {
        $id = Tools::getValue('id_product');
        // Logic to display the edit form
        return $this->renderModuleTemplate('admin/edit_form.tpl', ['product_id' => $id]);
    }
}
```
To link to the edit section from your list_products.tpl template, you can do this:

```html
<a href="{$link->getAdminLink('AdminCustomProducts')|escape:'html':'UTF-8'}&section=edit&id_product={$product.id}">
    Edit Product
</a>
```
### 4.4. Form Building with HelperForm

PrestaSDK extends PrestaShop's standard HelperForm class with more utility methods. To use it, simply create an instance of `PrestaSDK\V040\Utility\HelperForm`.

#### Steps to create a settings form:

- Process Form: Check if the form has been submitted.
- Build Form: Create a method to define the form structure ($fields_form).
- Render Form: Render the form using generateForm().

#### Complete example for a settings page:

```php
// controllers/admin/AdminMySettingsController.php
class AdminMySettingsController extends AdminController
{
    // ...
    
    // Main section of the controller
    public function sectionIndex()
    {
        $output = '';
        // If the form is submitted, save the values
        if (Tools::isSubmit('submit' . $this->module->name)) {
            $this->saveSettings();
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        
        // Render the form and add it to the output
        $output .= $this->renderSettingsForm();
        return $output;
    }

    protected function saveSettings()
    {
        $configs = [
            'MYMODULE_API_KEY' => Tools::getValue('MYMODULE_API_KEY'),
            'MYMODULE_ENABLE_FEATURE' => Tools::getValue('MYMODULE_ENABLE_FEATURE'),
        ];
        Config::updateConfigs($configs);
    }
    
    protected function renderSettingsForm()
    {
        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs'
            ],
            'input' => [
                ['type' => 'text', 'label' => $this->l('API Key'), 'name' => 'MYMODULE_API_KEY', 'required' => true],
                ['type' => 'switch', 'label' => $this->l('Enable Feature'), 'name' => 'MYMODULE_ENABLE_FEATURE', 'is_bool' => true, 'values' => [/* Yes/No values */]]
            ],
            'submit' => ['title' => $this->l('Save'), 'class' => 'btn btn-default pull-right']
        ];

        $helper = new \PrestaSDK\V040\Utility\HelperForm($this->module);
        
        // Automatically fill values from the ps_configuration table
        $helper->setFieldsByArray(['MYMODULE_API_KEY', 'MYMODULE_ENABLE_FEATURE']);
        
        return $helper->generateForm($fields_form);
    }
}
```
### 4.5. Displaying Lists (HelperList)

You can use PrestaShop's standard HelperList to display data lists. The AdminController in the SDK is designed to be fully compatible with it.

Simply define the list-related properties in your controller's __construct method, just like you would with a regular ModuleAdminController.

#### Example (AdminBulkUpdateFilesController.php):

```php
// controllers/admin/AdminBulkUpdateFilesController.php
class AdminBulkUpdateFilesController extends AdminController
{
    public function __construct()
    {
        $this->table = 'wabulkupdate_file';
        $this->className = 'PrestaWare\WaBulkUpdate\Entity\File';
        $this->identifier = 'id_wabulkupdate_file';
        $this->bootstrap = true;
        
        parent::__construct();

        $this->fields_list = [
            'id_wabulkupdate_file' => ['title' => $this->l('ID'), 'align' => 'center', 'class' => 'fixed-width-xs'],
            'file_name' => ['title' => $this->l('File Name')],
            'status' => ['title' => $this->l('Status'), 'align' => 'center', 'type' => 'bool', 'active' => 'status'],
            'date_add' => ['title' => $this->l('Date Add'), 'type' => 'datetime'],
        ];

        // Add actions to the list
        $this->addRowAction('view');
        $this->addRowAction('delete');
    }
}
```

Here, the renderList() or renderForm() methods are automatically called by AdminController, and their output is displayed within the SDK's panel layout.tpl. You don't need to override initContent() unless you want to change the default behavior.
### 4.6. Working with Templates

PanelCore provides two main methods for rendering templates:

- **renderModuleTemplate($template, $vars)**: Renders a template file from your module's views/templates/ path. This is suitable for your module's custom templates.

```php
$vars = ['my_variable' => 'Hello World'];
return $this->renderModuleTemplate('admin/my_page.tpl', $vars);
```

- **renderPanelTemplate($template, $vars)**: Renders a template from the SDK's default template path (vendor/prestaware/prestasdk/src/Resources/views/). This is useful for using pre-built components like _partials/sidebar.tpl.

#### Injecting Content into the Layout:

As mentioned earlier, you can use the `appendToPanel($position, $html)` method to add any HTML content to one of the layout positions (Header, Sidebar, Footer, etc.). This gives you great flexibility in customizing the panel's appearance.

```php
$customHeader = "<div class='alert alert-info'>Welcome to the panel!</div>";
$this->appendToPanel('TopContent', $customHeader);
```
## Chapter 5: Data Management (Models)
This chapter shows you how to easily work with the database using the BaseModel class in PrestaSDK. This class extends PrestaShop's ObjectModel and automates many repetitive tasks.
### 5.1. Creating a Model
To define a new entity that maps to a database table, create a class in the src/Entity/ directory (or any other path you prefer) and extend it from PrestaSDK\V040\Model\BaseModel.
Main Steps:
- Extend: Your class must extend BaseModel.
- Define Constants: Define the TABLE and ID constants to specify the table name and its primary key.
- Define $definition: Define the model's structure, including the table name, primary key, and fields, in the static $definition property. This structure is identical to the standard PrestaShop ObjectModel.
- (Optional) Define Special Columns: To enable BaseModel's automatic features, define the names of the date, status, and shop columns in the respective constants.
Complete Example (from the wabulkupdate module):

```php
// src/Entity/File.php
namespace PrestaWare\WaBulkUpdate\Entity;
use PrestaSDK\V040\Model\BaseModel;

class File extends BaseModel
{
    // 1. Define main constants
    const TABLE = 'wabulkupdate_file';
    const ID = 'id_wabulkupdate_file';

    // 2. Define special constants to enable BaseModel features
    const CREATED_AT_COLUMN = 'date_add';
    const UPDATED_AT_COLUMN = 'date_upd';
    const STATUS_COLUMN = 'status';
    // const ID_SHOP_COLUMN = 'id_shop'; // If your table is shop-specific

    // 3. Define class properties
    public $id;
    public $file_name;
    public $status;
    public $date_add;
    public $date_upd;

    // 4. Define the model structure for PrestaShop
    public static $definition = [
        'table' => self::TABLE,
        'primary' => self::ID,
        'fields' => [
            'file_name' => ['type' => self::TYPE_STRING, 'validate' => 'isFileName', 'required' => true, 'size' => 255],
            'status' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];
}
```
### 5.2. BaseModel Features
The BaseModel class adds many automatic functionalities to your model:
#### Automatic Date Management
If you define the CREATED_AT_COLUMN and UPDATED_AT_COLUMN constants:
- When creating a new record, both the date_add and date_upd fields are automatically populated with the current timestamp.
- When updating a record, the date_upd field is automatically updated.
#### Automatic Status Management
If you define the STATUS_COLUMN constant, you can use the toggleStatus() method to enable/disable a record. This method automatically flips the value of the status column and saves the record, which is very useful in a HelperList.
```php
$file = new File($id);
$file->toggleStatus(); // Status changes from 0 to 1 or vice-versa
```
#### Safe Saving with Validation (safeSave)
Instead of calling save() directly, you can use safeSave(). This method automatically runs all validations defined in $definition before saving. If the data is invalid, it returns false and prevents an invalid record from being saved.
```php
$file = new File();
$file->file_name = 'invalid-name-@!#.xlsx'; // Invalid
$file->status = 1;

if ($file->safeSave()) {
    // This code will not be executed
    echo "File saved successfully!";
} else {
    echo "Validation failed!";
}
```
#### Automatic id_shop Management
If your module operates in a multishop context and your table has an id_shop column, just define the ID_SHOP_COLUMN constant. BaseModel will automatically store the current shop's ID when creating a new record.
## Chapter 6: Advanced Topics
This chapter covers some of the more advanced features and helper classes in PrestaSDK that will help you develop more complex modules.
### 6.1. Asset Management (CSS/JS)
A common challenge in web development is managing browser cache for CSS and JavaScript files. PrestaSDK solves this problem with an automated system.
#### AssetPublisher and Automatic Versioning
The AssetPublisher class is responsible for copying prestasdk.css and prestasdk.js files from the vendor directory into your module's views/css and views/js directories. This is done during module installation.
More importantly, the setMedia() method in AdminController automatically includes these files in your pages and appends a version number to their URLs (e.g., ?v=0.4.0). This version number is read from the SDK's own composer.json file.
What's the benefit?
Whenever you update the PrestaSDK version via Composer, the version number in the file URLs changes. This forces users' browsers to download the new files, completely solving the caching issue.
If the SDK version has changed, AdminController will automatically re-run AssetPublisher to replace the old files with the new ones.
### 6.2. Request Lifecycle and Middlewares
PanelCore (used in AdminController) provides a powerful Middleware-like system for managing the request lifecycle. This system allows you to execute code before or after the main logic of a "section" runs.
This is extremely useful for tasks like access validation, processing POST data before a form is displayed, or loading data that is shared across multiple sections.
#### How to Use middlewaresACL
To use this feature, you need to define the $middlewaresACL property in your controller. This is an array that specifies which methods (middlewares) should run and when.
Array Structure:
```php
$this->middlewaresACL = [
    'before' => [
        // 'section@Controller' => ['middleware_name1', 'middleware_name2'],
    ],
    'after' => [],
    'ignore' => [], // To skip a middleware under certain conditions
];
```
- before: Methods defined here run before the main section... method.
- after: Methods defined here run after the main section... method.
- Definition Patterns:
- *: For all sections in all controllers.
- *@AdminMyController: For all sections in AdminMyController.
- settings@AdminMyController: Only for the settings section in AdminMyController.
Practical Example:
Let's say we want to check if a requested item exists before displaying the edit form (sectionEdit).
```php
class AdminCustomProductsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middlewaresACL = [
            'before' => [
                'edit@AdminCustomProducts' => ['loadProduct'], // Run before sectionEdit
            ],
        ];
    }
    
    /**
     * Middleware methods must be prefixed with 'middleware'.
     */
    public function middlewareLoadProduct()
    {
        $id_product = (int)Tools::getValue('id_product');
        $product = new Product($id_product);
        
        if (!Validate::isLoadedObject($product)) {
            // If product doesn't exist, redirect to the list
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminCustomProducts'));
        }
        
        // Make the product available for the main method
        $this->product = $product;
        
        return $this->runNext(); // Execute the next middleware or method in the queue
    }

    public function sectionEdit()
    {
        // Thanks to the middleware, we are sure that $this->product is loaded here
        // ...
    }
}
```
Important Note: At the end of each middleware method, you must call $this->runNext() to continue the request lifecycle.
### 6.3. Utility Classes
PrestaSDK includes several other helper classes to simplify everyday tasks.
#### HelperMethods

This class contains static methods for common tasks:

- **setFlashMessage($message, $type)**: Sets a temporary message (Flash Message) to be displayed to the user (e.g., after a redirect).
- **getFlashMessage()**: Reads the flash message and clears it from storage. The SDK admin panel automatically displays these messages.
- **setCookie($name, $data) / getCookie($name, $key)**: For easier interaction with PrestaShop cookies.

#### VersionHelper

This class has a static method getSDKVersion() that reads the current SDK version from its composer.json file. It is used internally by AssetPublisher.
## Chapter 7: Conclusion & Next Steps
Having reached this point, you are now familiar with all the core concepts and key features of PrestaSDK. This documentation has shown you how to simplify, accelerate, and standardize your PrestaShop module development process using this tool.
### 7.1. Summary of Benefits

- **Save Time**: Automating installation processes, model management, and admin panel creation frees up more time to focus on your core business logic.
- **Clean and Standardized Code**: By following the SDK's structure, your modules will have a consistent and predictable architecture, making teamwork and long-term maintenance easier.
- **Powerful Admin Panel**: You get a beautiful, responsive, and functional admin panel that is easily extendable, without needing to write complex code.
- **Easy Maintenance**: Automatic asset management and the use of BaseModel simplify the process of updating and debugging modules in the future.

### 7.2. Practical Example: WaBulkUpdate Module

The best way to fully grasp the power of PrestaSDK is to examine a real-world project. The WaBulkUpdate module, referenced throughout this documentation, is a complete, open-source example developed using this very SDK.

This module utilizes all the key features of the SDK, including:

- Automatic installers for tabs and tables
- Admin controllers based on AdminController
- Data management with BaseModel
- Admin panel structure with PanelCore and a sidebar menu

We highly recommend reviewing its source code to see how the concepts explained in this documentation are implemented in practice.

- View the WaBulkUpdate Module Repository on GitHub

### 7.3. Contribution and Bug Reports

PrestaSDK is an open-source project, and we welcome your contributions. If you encounter any issues, have ideas for improvements, or want to contribute to its development, please feel free to connect with us through the GitHub repository.

- Report an Issue or Suggest a Feature on GitHub

We hope you enjoy working with PrestaSDK and build powerful modules with it!