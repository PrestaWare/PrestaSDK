# مستندات PrestaSDK

## فصل ۱: مقدمه و شروع سریع

این فصل یک دید کلی درباره PrestaSDK، نحوه نصب و راه‌اندازی اولیه آن ارائه می‌دهد. در پایان این فصل، شما اولین ماژول خود را با استفاده از این SDK خواهید ساخت.

### ۱.۱. PrestaSDK چیست؟

PrestaSDK یک کیت توسعه نرم‌افزار (SDK) برای ساخت ماژول‌های پرستاشاپ است که با هدف افزایش سرعت، استانداردسازی و بهبود قابلیت نگهداری کد طراحی شده است. توسعه ماژول در پرستاشاپ گاهی می‌تواند شامل کدهای تکراری و فرآیندهای پیچیده برای انجام کارهای رایج مانند نصب، مدیریت تنظیمات، یا ساخت پنل مدیریت باشد. PrestaSDK با فراهم آوردن یک ساختار منظم و مجموعه‌ای از ابزارهای کمکی، این فرآیندها را خودکار و ساده می‌کند.

#### اهداف اصلی:

- **توسعه سریع (Rapid Development)**: با خودکارسازی وظایف تکراری مانند ثبت هوک‌ها، نصب تب‌های ادمین و مدیریت جداول دیتابیس، به شما اجازه می‌دهد تا روی منطق اصلی ماژول خود تمرکز کنید.
- **ساختار استاندارد (Standardized Structure)**: تمام ماژول‌هایی که با این SDK توسعه داده می‌شوند، از یک معماری یکسان پیروی می‌کنند. این موضوع باعث می‌شود که یادگیری، توسعه و کار تیمی روی پروژه‌ها آسان‌تر شود.
- **قابلیت نگهداری بالا (High Maintainability)**: کدهای نوشته‌شده خواناتر، تمیزتر و سازمان‌یافته‌تر خواهند بود که این امر نگهداری و بروزرسانی ماژول در آینده را ساده‌تر می‌کند.

#### مزایای کلیدی:

- **نصب‌کننده خودکار**: کامپوننت‌های Installer به‌سادگی فرآیند نصب تب‌ها، هوک‌ها و جداول دیتابیس را مدیریت می‌کنند.
- **پنل مدیریت آماده**: با استفاده از AdminController و PanelCore، یک ساختار پنل مدیریت زیبا و کاربردی همراه با منوی کناری و سیستم قالب‌بندی در اختیار شما قرار می‌گیرد.
- **کلاس‌های کمکی قدرتمند**: ابزارهایی برای کار با فرم‌ها (HelperForm)، تنظیمات (Config) و متدهای عمومی (HelperMethods) در نظر گرفته شده است.
- **مدیریت خودکار Asset ها**: فایل‌های CSS و JavaScript به صورت خودکار در مسیر صحیح کپی شده و نسخه‌بندی می‌شوند تا از مشکلات کش در مرورگر جلوگیری شود.
### ۱.۲. نصب و راه‌اندازی

برای استفاده از PrestaSDK در ماژول خود، تنها کافی است آن را به عنوان یک وابستگی (dependency) از طریق Composer اضافه کنید.

#### پیش‌نیازها:

- **PHP**: نسخه 7.4 یا بالاتر
- **PrestaShop**: نسخه 1.7.8 یا بالاتر (توصیه شده: 8.1.0 به بالا)
- **Composer**: نصب شده روی سیستم شما

#### مراحل نصب:

۱. وارد پوشه اصلی ماژول خود شوید.

۲. دستور زیر را در ترمینال اجرا کنید تا SDK به پروژه شما اضافه شود:

```bash
composer require prestaware/prestasdk
```

این دستور پوشه vendor و فایل autoload.php را در ریشه ماژول شما ایجاد می‌کند.

۳. در فایل اصلی ماژول خود (مثلاً mymodule.php)، فایل autoload.php را فراخوانی کنید تا کلاس‌های SDK در دسترس قرار گیرند:

```php
// mymodule/mymodule.php

if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
    require_once dirname(__FILE__).'/vendor/autoload.php';
}
```
### ۱.۳. ساخت اولین ماژول (Hello World)

در این بخش، یک ماژول ساده می‌سازیم که پایه و اساس کار با PrestaSDK را نشان می‌دهد.

#### ۱. ساختار اولیه فایل‌ها

ابتدا ساختار پوشه‌بندی زیر را برای ماژول خود ایجاد کنید:

```
/modules
  /myhelloworld
    - myhelloworld.php   (فایل اصلی ماژول)
    - composer.json
    - logo.png
    - config.xml
```

#### ۲. ساخت کلاس اصلی ماژول

محتوای فایل myhelloworld.php را به شکل زیر ایجاد کنید. مهم‌ترین نکته، ارث‌بری کلاس اصلی از `PrestaSDK\V040\PrestaSDKModule` است.

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
        // تمام تنظیمات اولیه باید در متد initModule قرار گیرند
        // این متد توسط والد صدا زده می‌شود
        parent::__construct();
    }

    /**
     * تنظیمات اصلی و اولیه ماژول در این متد تعریف می‌شود.
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
     * متد نصب - SDK بقیه کارها را انجام می‌دهد
     */
    public function install()
    {
        return parent::install();
    }

    /**
     * متد حذف - SDK بقیه کارها را انجام می‌دهد
     */
    public function uninstall()
    {
        return parent::uninstall();
    }
}
```

#### ۳. معرفی متد initModule

همان‌طور که در کد بالا مشاهده می‌کنید، تمام پراپرتی‌های اصلی ماژول مانند `name`, `version`, `displayName` و... داخل متد `initModule()` تعریف شده‌اند.

این متد به صورت خودکار توسط سازنده (`__construct`) کلاس `PrestaSDKModule` فراخوانی می‌شود. این کار باعث می‌شود تا منطق مقداردهی اولیه ماژول از سازنده اصلی جدا شده و کد شما تمیزتر و سازمان‌یافته‌تر باشد.

با همین چند خط کد، شما یک ماژول استاندارد ساخته‌اید. حالا می‌توانید آن را نصب کنید. در فصل‌های بعدی یاد می‌گیریم که چگونه کنترلر، تنظیمات، تب‌های مدیریت و... را به آن اضافه کنیم.
## فصل ۲: مفاهیم اصلی و پایه

این فصل به بررسی عمیق‌تر اجزای اصلی PrestaSDK می‌پردازد. درک این مفاهیم به شما کمک می‌کند تا از تمام ظرفیت‌های SDK برای ساخت ماژول‌های پیچیده و قدرتمند استفاده کنید.

### ۲.۱. کلاس PrestaSDKModule

این کلاس، نقطه شروع هر ماژول مبتنی بر SDK است و با ارث‌بری از کلاس Module پرستاشاپ، قابلیت‌های فراوانی را به آن اضافه می‌کند. شما با تعریف چند پراپرتی در کلاس ماژول خود، می‌توانید فرآیندهای نصب و پیکربندی را به طور کامل به SDK بسپارید.

#### پراپرتی‌های کلیدی:

- **$moduleTabs (آرایه)**: برای تعریف تمام تب‌های (منوهای) بخش مدیریت که ماژول شما به آن نیاز دارد. TabsInstaller به صورت خودکار این تب‌ها را هنگام نصب ماژول ایجاد می‌کند.
- **$moduleConfigs (آرایه)**: لیستی از تمام کلیدهای پیکربندی (Configuration) که ماژول شما استفاده می‌کند. مقادیر پیش‌فرض آن‌ها در این آرایه تعریف شده و هنگام نصب به صورت خودکار در دیتابیس ذخیره می‌شوند.
- **$configsAdminController (رشته یا آرایه)**: نام کنترلر اصلی ماژول را مشخص می‌کند. با این کار، دکمه "پیکربندی" (Configure) در لیست ماژول‌ها به صورت خودکار به صفحه این کنترلر لینک داده می‌شود.
- **$moduleGrandParentTab (رشته)**: اگر می‌خواهید تب‌های ماژول شما زیرمجموعه یک تب اصلی دیگر (مثلاً "فروش") قرار گیرند، نام کلاس آن تب را در این پراپرتی وارد کنید.
- **$pathFileSqlInstall / $pathFileSqlUninstall (رشته)**: مسیر فایل‌های SQL برای ایجاد یا حذف جداول دیتابیس هنگام نصب/حذف ماژول را مشخص می‌کند. به طور پیش‌فرض، SDK به دنبال فایل‌های `install.sql` و `uninstall.sql` در پوشه `sql/` ماژول می‌گردد.
#### چرخه حیات ماژول

متدهای `install()` و `uninstall()` در PrestaSDKModule بازنویسی (override) شده‌اند تا فرآیندهای لازم را به صورت خودکار اجرا کنند. زمانی که شما `parent::install()` را فراخوانی می‌کنید، SDK به ترتیب کارهای زیر را انجام می‌دهد:

- **نصب تب‌ها**: بر اساس آرایه `$moduleTabs`.
- **ثبت هوک‌ها**: با بررسی تمام متدهای `hook...` در کلاس ماژول شما.
- **اجرای SQL**: با استفاده از فایل تعریف شده در `$pathFileSqlInstall`.
- **ذخیره تنظیمات**: مقادیر اولیه از `$moduleConfigs` در دیتابیس ذخیره می‌شوند.
- **انتشار Asset ها**: فایل‌های CSS/JS مربوط به SDK در پوشه views ماژول شما کپی می‌شوند.

بنابراین، شما دیگر نیازی به نوشتن منطق تکراری برای این کارها ندارید.
### ۲.۲. ساختار پنل مدیریت
PrestaSDK یک سیستم قدرتمند برای ساخت پنل‌های مدیریت مدرن و چندبخشی ارائه می‌دهد.

#### AdminController و PanelCore

برای ساخت یک صفحه در بخش مدیریت، کافی است یک کنترلر ایجاد کنید که از کلاس `PrestaSDK\V040\Controller\AdminController` ارث‌بری کند. این کلاس به طور خودکار Trait ی به نام PanelCore را به کار می‌گیرد که تمام منطق رندر کردن پنل، مدیریت بخش‌ها و قالب‌بندی را در خود جای داده است.

#### مفهوم بخش‌ها (Sections)

یکی از قابلیت‌های کلیدی AdminController، مدیریت صفحات از طریق "بخش‌ها" است. به جای ساخت چندین کنترلر برای صفحات مختلف (مانند تنظیمات، لیست‌ها، افزودن آیتم جدید)، شما می‌توانید تمام منطق را در یک کنترلر واحد و در متدهایی با الگوی `section<Name>` پیاده‌سازی کنید.

- **مثال**: اگر URL شما `...&section=settings` باشد، SDK به صورت خودکار متد `sectionSettings()` را در کنترلر شما فراخوانی و محتوای آن را نمایش می‌دهد.
- **بخش پیش‌فرض**: اگر پارامتر section در URL وجود نداشته باشد، متد `sectionIndex()` اجرا خواهد شد.

این رویکرد، کد شما را بسیار تمیزتر و سازمان‌یافته‌تر می‌کند.

#### سیستم Layout و جایگاه‌ها (Positions)

پنل مدیریت SDK از یک فایل `layout.tpl` اصلی تشکیل شده که دارای جایگاه‌های (Positions) مختلفی مانند Sidebar, Header, TopContent و Footer است. شما می‌توانید از داخل کنترلر خود، محتوای HTML را به هر یک از این جایگاه‌ها تزریق کنید.

برای مثال، برای افزودن منوی کناری به پنل، کافیست HTML منو را رندر کرده و با متد زیر آن را به جایگاه Sidebar اضافه کنید:

```php
$sidebarHtml = $this->renderPanelTemplate('_partials/sidebar.tpl', $vars);
$this->appendToPanel('Sidebar', $sidebarHtml);
```

این قابلیت به شما اجازه می‌دهد تا یک رابط کاربری یکپارچه و در عین حال کاملاً سفارشی ایجاد کنید.
### ۲.۳. مدیریت نسخه‌ها، Namespace و Factory
#### Namespace نسخه‌بندی شده

یک چالش مهم در اکوسیستم پرستاشاپ، احتمال تداخل بین ماژول‌های مختلف است. اگر دو ماژول از یک کتابخانه مشترک (مانند PrestaSDK) استفاده کنند، اما نسخه‌های متفاوتی از آن کتابخانه را نیاز داشته باشند، ممکن است با خطاهای Fatal Error به دلیل تعریف مجدد کلاس‌ها یا توابع مواجه شوند.

برای حل این مشکل، تمام کلاس‌های PrestaSDK در یک Namespace نسخه‌بندی شده قرار دارند. برای مثال، در نسخه 0.4.0، تمام کلاس‌ها زیر `PrestaSDK\V040` قرار دارند:

```php
namespace PrestaSDK\V040;

class PrestaSDKModule extends \Module
{
    // ...
}
```

این ساختار تضمین می‌کند که اگر در آینده نسخه 0.5.0 با namespace `PrestaSDK\V050` منتشر شود، کدهای آن با نسخه‌های قدیمی‌تر تداخلی نخواهند داشت.

#### مسئولیت توسعه‌دهنده

هنگام استفاده از SDK، این وظیفه شما به عنوان توسعه‌دهنده است که از Namespace صحیح و متناسب با نسخه SDK نصب شده در ماژول خود استفاده کنید.

```php
// استفاده صحیح برای نسخه 0.4.0
use PrestaSDK\V040\PrestaSDKModule;
use PrestaSDK\V040\Controller\AdminController;

class MyModule extends PrestaSDKModule 
{
    //...
}
```

اگر در آینده تصمیم گرفتید نسخه SDK را در ماژول خود ارتقا دهید، باید به صورت دستی use statement های خود را نیز به نسخه جدید (مثلاً `PrestaSDK\V050\...`) بروزرسانی کنید.

#### PrestaSDKFactory

کلاس PrestaSDKFactory یک ابزار کمکی برای ساده‌سازی فرآیند ساخت نمونه از کلاس‌های SDK در همان نسخه است. به جای فراخوانی مستقیم `new \PrestaSDK\V040\Utility\Config(...)`، می‌توانید از Factory استفاده کنید تا کد خواناتری داشته باشید.

```php
use PrestaSDK\V040\PrestaSDKFactory;

// این Factory یک نمونه از کلاس Config در Namespace نسخه V040 ایجاد می‌کند
$config = PrestaSDKFactory::getUtility('Config', [$this->moduleConfigs]);
```

این الگو به خوانایی کد کمک می‌کند اما مسئولیت انتخاب نسخه صحیح Namespace همچنان بر عهده شماست.
## فصل ۳: فرآیند نصب ماژول (Installation)
این فصل به شما نشان می‌دهد که چگونه با استفاده از کلاس‌های Installer در PrestaSDK، فرآیند نصب ماژول خود را به طور کامل خودکار کنید. کافی است چند پراپرتی را در کلاس اصلی ماژول خود تعریف کنید و SDK بقیه کارها را انجام خواهد داد.
### ۳.۱. نصب جداول دیتابیس (TablesInstaller)
برای ایجاد جداول مورد نیاز ماژول در دیتابیس، کافیست فایل‌های SQL خود را در پوشه sql/ در ریشه ماژول قرار دهید.
- sql/install.sql: دستورات SQL برای ایجاد جداول.
- sql/uninstall.sql: دستورات SQL برای حذف جداول هنگام حذف ماژول.
TablesInstaller به صورت خودکار این فایل‌ها را پیدا کرده و اجرا می‌کند. این کلاس هوشمندانه مقادیر `_DB_PREFIX_` و `_MYSQL_ENGINE_` را با مقادیر صحیح جایگزین می‌کند.

مثال (install.sql):

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

**نکته**: اگر می‌خواهید فایل‌های SQL را در مسیر دیگری قرار دهید، می‌توانید مسیر کامل آن‌ها را در پراپرتی‌های `$pathFileSqlInstall` و `$pathFileSqlUninstall` در کلاس اصلی ماژول خود مشخص کنید.
### ۳.۲. افزودن تب‌های مدیریت (TabsInstaller)
برای ساخت منوهای ماژول در پنل مدیریت، کافیست ساختار آن‌ها را در پراپرتی `$moduleTabs` در کلاس اصلی ماژول خود تعریف کنید.

ساختار این آرایه به صورت `['AdminClassName' => [options]]` است.

- **AdminClassName**: نام کلاس کنترلر شما (بدون پسوند Controller).
- **options**: آرایه‌ای از تنظیمات تب:
  - **title**: عنوانی که در منو نمایش داده می‌شود.
  - **parent_class_name**: نام کلاس کنترلر والد. اگر این تب یک زیرمجموعه است، نام کلاس والد را اینجا قرار دهید. برای ایجاد یک تب اصلی، از یک نام دلخواه استفاده کرده و آن را در پراپرتی `$moduleGrandParentTab` نیز تعریف کنید.
  - **icon**: (اختیاری) کلاس آیکون مورد نظر (مثلاً `icon-cogs`).
  - **visible**: (اختیاری) با مقدار `false` می‌توانید تب را مخفی کنید.

**مثال (از ماژول wabulkupdate)**:

در این مثال، ابتدا یک تب اصلی به نام AdminBulkUpdate تعریف شده و سپس بقیه تب‌ها به عنوان فرزند آن قرار گرفته‌اند.

```php
// wabulkupdate.php

public function initModule()
{
    // ...
    $this->moduleGrandParentTab = 'AdminBulkUpdate'; // نام کلاس تب اصلی
    
    $this->moduleTabs = [
        // تب اصلی
        'AdminBulkUpdate' => [
            'title' => $this->l('Bulk Update'),
            'parent_class_name' => '', // والد ندارد، پس در ریشه قرار می‌گیرد
            'icon' => 'icon-cloud-upload',
        ],
        // تب‌های فرزند
        'AdminBulkUpdatePanel' => [
            'title' => $this->l('Panel'),
            'parent_class_name' => $this->moduleGrandParentTab, // فرزند AdminBulkUpdate است
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
### ۳.۳. ثبت خودکار هوک‌ها (HooksInstaller)
یکی از بزرگترین مزایای PrestaSDK، عدم نیاز به ثبت دستی هوک‌هاست. کلاس HooksInstaller با استفاده از Reflection API در PHP، کلاس اصلی ماژول شما را بررسی کرده و تمام متدهای پابلیک که با پیشوند `hook` شروع می‌شوند را پیدا می‌کند و هوک متناظر با آن‌ها را به صورت خودکار ثبت می‌کند.

برای مثال، اگر بخواهید به هوک `actionProductUpdate` متصل شوید، کافیست متد زیر را در کلاس اصلی ماژول خود ایجاد کنید:

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

هنگام نصب، هوک‌های `actionProductUpdate` و `displayHeader` به صورت خودکار برای ماژول شما ثبت خواهند شد.
### ۳.۴. مدیریت تنظیمات اولیه (Config)
برای تعریف مقادیر پیش‌فرض برای تنظیمات ماژول، از پراپرتی `$moduleConfigs` استفاده کنید. این پراپرتی یک آرایه از `key => value` است که در آن key نام متغیر در جدول `ps_configuration` و value مقدار پیش‌فرض آن است.

مثال:

```php
// mymodule.php

public function initModule()
{
    // ...
    $this->perfixConfigs = 'MYMODULE'; // (اختیاری) یک پیشوند برای جلوگیری از تداخل نام
    
    $this->moduleConfigs = [
        'MYMODULE_ENABLE_FEATURE' => 1,
        'MYMODULE_API_KEY' => 'default_api_key',
        'MYMODULE_ITEMS_PER_PAGE' => 10,
    ];
}
```

در زمان نصب، کلاس Config تمام این مقادیر را به صورت خودکار در دیتابیس ذخیره می‌کند. برای خواندن این مقادیر در هر جای ماژول، می‌توانید از متد کمکی زیر استفاده کنید:

```php
$apiKey = $this->config->getConfig('API_KEY'); 
// خروجی: 'default_api_key'
// نیازی به نوشتن پیشوند نیست
```
## فصل ۴: توسعه پنل مدیریت
این فصل شما را با فرآیند ساخت یک پنل مدیریت کامل با استفاده از ابزارهای PrestaSDK آشنا می‌کند. از ساخت کنترلر و منو گرفته تا مدیریت فرم‌ها و لیست‌ها، همه چیز در اینجا پوشش داده می‌شود.
### ۴.۱. ساخت کنترلر مدیریت
اولین قدم برای ایجاد یک صفحه در بخش مدیریت، ساخت یک کلاس کنترلر است. این کلاس باید از PrestaSDK\V040\Controller\AdminController ارث‌بری کند. این کلاس پایه، تمام قابلیت‌های ModuleAdminController پرستاشاپ را به همراه ویژگی‌های PanelCore در اختیار شما قرار می‌دهد.
مراحل:
- یک فایل PHP در مسیر controllers/admin/ ماژول خود ایجاد کنید. نام فایل باید با نام کلاس کنترلر شما یکسان باشد (مثلاً AdminMyPanelController.php).
- کلاس خود را با ارث‌بری از AdminController تعریف کنید.
مثال پایه:
```php
// controllers/admin/AdminMyPanelController.php

use PrestaSDK\V040\Controller\AdminController;

class AdminMyPanelController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // تنظیمات اولیه کنترلر در اینجا قرار می‌گیرد
    }

    /**
     * این متد جایگزین getContent() می‌شود و نقطه ورود اصلی برای رندر پنل است.
     */
    public function initContent()
    {
        // به جای فراخوانی مستقیم parent::initContent()، از متد initAdminPanel() استفاده می‌کنیم
        // این متد تمام اجزای پنل (سایدبار، محتوا و ...) را مدیریت و رندر می‌کند.
        $this->content = $this->initAdminPanel();
    }
}
```
پس از تعریف کنترلر و اتصال آن به یک تب (همانطور که در فصل ۳ توضیح داده شد)، پرستاشاپ به صورت خودکار آن را در منوی مدیریت نمایش خواهد داد.
### ۴.۲. منوی کناری (Sidebar)
برای ایجاد منوی ناوبری در سمت راست پنل، کافیست متد getmenuItems() را در کلاس کنترلر خود پیاده‌سازی کنید. این متد باید یک آرایه با ساختار مشخص برگرداند. PanelCore به صورت خودکار این منو را رندر کرده و در جایگاه Sidebar قرار می‌دهد.
ساختار آرایه:
آرایه باید شامل گروه‌هایی از آیتم‌های منو باشد. هر آیتم می‌تواند خود شامل زیرمجموعه باشد.
```php
// controllers/admin/AdminBulkUpdatePanelController.php

public function getmenuItems()
{
    $menuItems = [
        'main_group' => [ // کلید گروه
            'panel' => [ // کلید آیتم
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
نکات:
- $this->module->getModuleAdminLink(...) بهترین روش برای تولید لینک‌های داخلی پنل است.
- SDK به صورت خودکار آیتم فعال منو را بر اساس کنترلر فعلی تشخیص می‌دهد.
### ۴.۳. مدیریت صفحات با بخش‌ها (Sections)
همانطور که در فصل قبل اشاره شد، شما می‌توانید منطق صفحات مختلف را در یک کنترلر با استفاده از متدهایی با پیشوند section مدیریت کنید.
مثال:
فرض کنید یک کنترلر برای مدیریت محصولات سفارشی دارید. می‌توانید صفحات لیست، افزودن و ویرایش را به شکل زیر مدیریت کنید:
```php
class AdminCustomProductsController extends AdminController
{
    // ...
    
    // این متد برای URL بدون پارامتر section اجرا می‌شود (صفحه پیش‌فرض)
    public function sectionIndex()
    {
        // منطق نمایش لیست محصولات
        return $this->renderModuleTemplate('admin/list_products.tpl');
    }

    // این متد برای &section=edit اجرا می‌شود
    public function sectionEdit()
    {
        $id = Tools::getValue('id_product');
        // منطق نمایش فرم ویرایش
        return $this->renderModuleTemplate('admin/edit_form.tpl', ['product_id' => $id]);
    }
}
```
برای لینک‌دهی به بخش ویرایش از داخل قالب list_products.tpl می‌توانید به این صورت عمل کنید:
```smarty
<a href="{$link->getAdminLink('AdminCustomProducts')|escape:'html':'UTF-8'}&section=edit&id_product={$product.id}">
    Edit Product
</a>
```
### ۴.۴. فرم‌سازی با HelperForm
PrestaSDK کلاس HelperForm استاندارد پرستاشاپ را با متدهای کاربردی‌تری گسترش داده است. برای استفاده از آن، کافیست یک نمونه از کلاس PrestaSDK\V040\Utility\HelperForm بسازید.
مراحل ساخت یک فرم تنظیمات:
- پردازش فرم: ابتدا بررسی کنید که آیا فرم ارسال شده است یا خیر.
- ساخت فرم: یک متد برای تعریف ساختار فرم ($fields_form) ایجاد کنید.
- رندر فرم: فرم را با استفاده از generateForm() رندر کنید.
مثال کامل برای یک صفحه تنظیمات:
```php
// controllers/admin/AdminMySettingsController.php

class AdminMySettingsController extends AdminController
{
    // ...
    
    // بخش اصلی کنترلر
    public function sectionIndex()
    {
        $output = '';
        // اگر فرم ارسال شده باشد، مقادیر را ذخیره کن
        if (Tools::isSubmit('submit' . $this->module->name)) {
            $this->saveSettings();
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        
        // فرم را رندر کن و به خروجی اضافه کن
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
                [
                    'type' => 'text',
                    'label' => $this->l('API Key'),
                    'name' => 'MYMODULE_API_KEY',
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Enable Feature'),
                    'name' => 'MYMODULE_ENABLE_FEATURE',
                    'is_bool' => true,
                    'values' => [ /* مقادیر بله/خیر */ ],
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new \PrestaSDK\V040\Utility\HelperForm($this->module);
        
        // پر کردن خودکار مقادیر از جدول ps_configuration
        $helper->setFieldsByArray(['MYMODULE_API_KEY', 'MYMODULE_ENABLE_FEATURE']);
        
        return $helper->generateForm($fields_form);
    }
}
```
### ۴.۵. نمایش لیست‌ها (HelperList)
شما می‌توانید از HelperList استاندارد پرستاشاپ برای نمایش لیست داده‌ها استفاده کنید. AdminController در SDK به گونه‌ای طراحی شده که با آن کاملاً سازگار باشد.
کافیست مانند یک ModuleAdminController معمولی، پراپرتی‌های مربوط به لیست را در __construct کنترلر خود تعریف کنید.
مثال (AdminBulkUpdateFilesController.php):
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

        // افزودن اکشن‌ها به لیست
        $this->addRowAction('view');
        $this->addRowAction('delete');
    }
}
```
در اینجا، متدهای renderList() یا renderForm() به صورت خودکار توسط AdminController فراخوانی شده و خروجی آن‌ها در layout.tpl پنل SDK نمایش داده می‌شود. شما نیازی به بازنویسی initContent() ندارید مگر اینکه بخواهید رفتار پیش‌فرض را تغییر دهید.
### ۴.۶. کار با قالب‌ها (Templating)
PanelCore دو متد اصلی برای رندر کردن قالب‌ها ارائه می‌دهد:
- renderModuleTemplate($template, $vars): این متد یک فایل قالب را از مسیر views/templates/ ماژول شما رندر می‌کند. این برای قالب‌های اختصاصی ماژول شما مناسب است.
```php
$vars = ['my_variable' => 'Hello World'];
return $this->renderModuleTemplate('admin/my_page.tpl', $vars);
```
- renderPanelTemplate($template, $vars): این متد قالب را از مسیر پیش‌فرض قالب‌های SDK (vendor/prestaware/prestasdk/src/Resources/views/) رندر می‌کند. این برای استفاده از اجزای آماده مانند _partials/sidebar.tpl کاربرد دارد.
تزریق محتوا به Layout:
همانطور که قبلاً اشاره شد، با استفاده از متد appendToPanel($position, $html) می‌توانید هر محتوای HTML را به یکی از جایگاه‌های (Header, Sidebar, Footer و...) اضافه کنید. این کار به شما انعطاف‌پذیری بالایی در سفارشی‌سازی ظاهر پنل می‌دهد.
```php
$customHeader = "<div class='alert alert-info'>Welcome to the panel!</div>";
$this->appendToPanel('TopContent', $customHeader);
```
## فصل ۵: مدیریت داده‌ها (Models)
این فصل به شما نشان می‌دهد که چگونه با استفاده از کلاس BaseModel در PrestaSDK، به سادگی با دیتابیس کار کنید. این کلاس با ارث‌بری از ObjectModel پرستاشاپ، بسیاری از کارهای تکراری را خودکار می‌کند.
### ۵.۱. ساخت یک Model
برای تعریف یک موجودیت (Entity) جدید که به یک جدول در دیتابیس متصل است، یک کلاس در پوشه src/Entity/ (یا هر مسیر دلخواه دیگر) ایجاد کرده و آن را از PrestaSDK\V040\Model\BaseModel ارث‌بری کنید.
مراحل اصلی:
- ارث‌بری: کلاس شما باید از BaseModel ارث‌بری کند.
- تعریف ثابت‌ها: ثابت‌های TABLE و ID را برای مشخص کردن نام جدول و کلید اصلی آن تعریف کنید.
- تعریف $definition: ساختار مدل، شامل نام جدول، کلید اصلی و فیلدها را در پراپرتی استاتیک $definition تعریف کنید. این ساختار کاملاً مشابه ObjectModel استاندارد پرستاشاپ است.
- (اختیاری) تعریف ستون‌های ویژه: برای فعال‌سازی ویژگی‌های خودکار BaseModel، نام ستون‌های تاریخ، وضعیت و فروشگاه را در ثابت‌های مربوطه تعریف کنید.
مثال کامل (از ماژول wabulkupdate):

```php
// src/Entity/File.php

namespace PrestaWare\WaBulkUpdate\Entity;

use PrestaSDK\V040\Model\BaseModel;

class File extends BaseModel
{
    // 1. تعریف ثابت‌های اصلی
    const TABLE = 'wabulkupdate_file';
    const ID = 'id_wabulkupdate_file';

    // 2. تعریف ثابت‌های ویژه برای فعال‌سازی قابلیت‌های BaseModel
    const CREATED_AT_COLUMN = 'date_add';
    const UPDATED_AT_COLUMN = 'date_upd';
    const STATUS_COLUMN = 'status';
    // const ID_SHOP_COLUMN = 'id_shop'; // اگر جدول شما به فروشگاه مرتبط است

    // 3. تعریف پراپرتی‌های کلاس
    public $id;
    public $file_name;
    public $status;
    public $date_add;
    public $date_upd;

    // 4. تعریف ساختار مدل برای پرستاشاپ
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
### ۵.۲. ویژگی‌های BaseModel
کلاس BaseModel قابلیت‌های زیادی را به صورت خودکار به مدل شما اضافه می‌کند:
#### مدیریت خودکار تاریخ‌ها
اگر ثابت‌های CREATED_AT_COLUMN و UPDATED_AT_COLUMN را تعریف کنید:
- هنگام ایجاد یک رکورد جدید، هر دو فیلد date_add و date_upd به صورت خودکار با تاریخ و زمان فعلی پر می‌شوند.
- هنگام بروزرسانی یک رکورد، فیلد date_upd به صورت خودکار بروز می‌شود.
#### مدیریت خودکار وضعیت (Status)
اگر ثابت STATUS_COLUMN را تعریف کنید، می‌توانید از متد toggleStatus() برای فعال/غیرفعال کردن یک رکورد استفاده کنید. این متد به صورت خودکار مقدار ستون وضعیت را برعکس کرده و رکورد را ذخیره می‌کند. این قابلیت در HelperList بسیار کاربردی است.
```php
$file = new File($id);
$file->toggleStatus(); // وضعیت از 0 به 1 یا برعکس تغییر می‌کند
```
#### ذخیره‌سازی امن با اعتبارسنجی (safeSave)
به جای فراخوانی مستقیم متد save()، می‌توانید از safeSave() استفاده کنید. این متد قبل از ذخیره‌سازی، به صورت خودکار تمام اعتبارسنجی‌های تعریف شده در $definition را اجرا می‌کند. اگر داده‌ها معتبر نباشند، false برمی‌گرداند و از ذخیره رکورد نامعتبر جلوگیری می‌کند.
```php
$file = new File();
$file->file_name = 'invalid-name-@!#.xlsx'; // نامعتبر
$file->status = 1;

if ($file->safeSave()) {
    // این کد اجرا نخواهد شد
    echo "File saved successfully!";
} else {
    echo "Validation failed!";
}
```
#### مدیریت خودکار id_shop
اگر ماژول شما در حالت چندفروشگاهی (multishop) کار می‌کند و جدول شما ستون id_shop دارد، کافیست ثابت ID_SHOP_COLUMN را تعریف کنید. BaseModel به صورت خودکار هنگام ایجاد یک رکورد جدید، id_shop مربوط به فروشگاه فعلی را در آن ذخیره می‌کند.
## فصل ۶: مباحث پیشرفته
این فصل به بررسی برخی از قابلیت‌های پیشرفته‌تر و کلاس‌های کمکی PrestaSDK می‌پردازد که به شما در توسعه ماژول‌های پیچیده‌تر کمک می‌کنند.
### ۶.۱. مدیریت Asset ها (CSS/JS)
یکی از چالش‌های رایج در توسعه وب، مدیریت کش مرورگر برای فایل‌های CSS و JavaScript است. PrestaSDK این مشکل را با یک سیستم خودکار حل کرده است.
#### AssetPublisher و نسخه‌بندی خودکار
کلاس AssetPublisher وظیفه دارد تا فایل‌های prestasdk.css و prestasdk.js را از داخل پوشه vendor به پوشه views/css و views/js ماژول شما کپی کند. این کار هنگام نصب ماژول انجام می‌شود.
مهم‌تر از آن، متد setMedia() در AdminController به صورت خودکار این فایل‌ها را به صفحات اضافه می‌کند و یک شماره نسخه به انتهای URL آن‌ها اضافه می‌کند (مثلاً ?v=0.4.0). این شماره نسخه از فایل composer.json خود SDK خوانده می‌شود.
این فرآیند چه مزیتی دارد؟
هر زمان که شما نسخه PrestaSDK را از طریق Composer بروزرسانی کنید، شماره نسخه در URL فایل‌ها تغییر می‌کند. این کار باعث می‌شود که مرورگر کاربران مجبور شود نسخه جدید فایل‌ها را دانلود کند و مشکل کش به طور کامل حل شود.
اگر نسخه SDK تغییر کرده باشد، AdminController به صورت خودکار AssetPublisher را مجدداً فراخوانی می‌کند تا فایل‌های جدید جایگزین شوند.
### ۶.۲. چرخه درخواست و Middleware ها
PanelCore (که در AdminController استفاده می‌شود) یک سیستم قدرتمند شبیه به Middleware برای مدیریت چرخه حیات درخواست‌ها (Request Lifecycle) ارائه می‌دهد. این سیستم به شما اجازه می‌دهد تا کدهایی را قبل یا بعد از اجرای منطق اصلی یک "بخش" (Section) اجرا کنید.
این قابلیت برای مواردی مانند اعتبارسنجی دسترسی‌ها، پردازش داده‌های POST قبل از نمایش فرم، یا بارگذاری داده‌های مشترک بین چند بخش بسیار مفید است.
#### نحوه استفاده از middlewaresACL
برای استفاده از این قابلیت، باید پراپرتی $middlewaresACL را در کنترلر خود تعریف کنید. این پراپرتی یک آرایه است که مشخص می‌کند کدام متدها (Middleware ها) باید در چه زمانی اجرا شوند.
ساختار آرایه:
$this->middlewaresACL = [
    'before' => [
        // 'بخش@کنترلر' => ['نام_میدلور۱', 'نام_میدلور۲'],
    ],
    'after' => [],
    'ignore' => [], // برای نادیده گرفتن یک میدلور در شرایط خاص
];
- before: متدهای تعریف شده در این بخش، قبل از اجرای متد section... اصلی اجرا می‌شوند.
- after: متدهای تعریف شده در این بخش، بعد از اجرای متد section... اصلی اجرا می‌شوند.
- الگوی تعریف:
- *: برای تمام بخش‌ها در تمام کنترلرها.
- *@AdminMyController: برای تمام بخش‌ها در کنترلر AdminMyController.
- settings@AdminMyController: فقط برای بخش settings در کنترلر AdminMyController.
مثال عملی:
فرض کنید می‌خواهیم قبل از نمایش فرم ویرایش (sectionEdit)، بررسی کنیم که آیا آیتم درخواستی وجود دارد یا خیر.
```php
class AdminCustomProductsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middlewaresACL = [
            'before' => [
                'edit@AdminCustomProducts' => ['loadProduct'], // قبل از sectionEdit اجرا شود
            ],
        ];
    }
    
    /**
     * متد میدلور باید با پیشوند 'middleware' نام‌گذاری شود.
     */
    public function middlewareLoadProduct()
    {
        $id_product = (int)Tools::getValue('id_product');
        $product = new Product($id_product);
        
        if (!Validate::isLoadedObject($product)) {
            // اگر محصول وجود نداشت، به لیست برگردان
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminCustomProducts'));
        }
        
        // محصول را برای استفاده در متد اصلی در دسترس قرار بده
        $this->product = $product;
        
        return $this->runNext(); // اجرای میدلور یا متد بعدی در صف
    }

    public function sectionEdit()
    {
        // به لطف میدلور، اینجا مطمئن هستیم که $this->product لود شده است
        // ...
    }
}
```
نکته مهم: در پایان هر متد Middleware، باید $this->runNext() را فراخوانی کنید تا اجرای چرخه درخواست ادامه پیدا کند.
### ۶.۳. کلاس‌های کمکی (Utilities)
PrestaSDK شامل چند کلاس کمکی دیگر نیز می‌شود که کارهای روزمره را ساده‌تر می‌کنند.
#### HelperMethods

این کلاس شامل متدهای استاتیک برای کارهای عمومی است:

- **setFlashMessage($message, $type)**: یک پیام موقت (Flash Message) برای نمایش به کاربر تنظیم می‌کند (مثلاً بعد از یک redirect).
- **getFlashMessage()**: پیام تنظیم شده را می‌خواند و آن را از حافظه پاک می‌کند. پنل مدیریت SDK به صورت خودکار این پیام‌ها را نمایش می‌دهد.
- **setCookie($name, $data) / getCookie($name, $key)**: برای کار ساده‌تر با کوکی‌های پرستاشاپ.

#### VersionHelper

این کلاس یک متد استاتیک به نام `getSDKVersion()` دارد که نسخه فعلی SDK را از فایل composer.json آن می‌خواند. این کلاس به طور داخلی توسط AssetPublisher استفاده می‌شود.
## فصل ۷: جمع‌بندی و مراحل بعدی
با رسیدن به این بخش، شما با تمام مفاهیم اصلی و قابلیت‌های کلیدی PrestaSDK آشنا شده‌اید. این مستندات به شما نشان داد که چگونه می‌توانید با استفاده از این ابزار، فرآیند توسعه ماژول‌های پرستاشاپ را به شکل قابل توجهی ساده‌تر، سریع‌تر و استانداردتر کنید.
### ۷.۱. جمع‌بندی مزایا
- صرفه‌جویی در زمان: با خودکارسازی فرآیندهای نصب، مدیریت مدل‌ها و ساخت پنل مدیریت، زمان بیشتری برای تمرکز بر منطق اصلی کسب‌وکار خود خواهید داشت.
- کدنویسی تمیز و استاندارد: با پیروی از ساختار مشخص SDK، ماژول‌های شما از یک معماری یکسان و قابل پیش‌بینی برخوردار خواهند بود که کار تیمی و نگهداری بلندمدت را آسان‌تر می‌کند.
- پنل مدیریت قدرتمند: بدون نیاز به کدنویسی پیچیده، یک پنل مدیریت زیبا، واکنش‌گرا و کاربردی در اختیار دارید که به راحتی قابل توسعه است.
- نگهداری آسان: مدیریت خودکار Asset ها و استفاده از BaseModel، بروزرسانی و رفع اشکال ماژول‌ها را در آینده ساده‌تر می‌کند.
### ۷.۲. مثال عملی: ماژول WaBulkUpdate
بهترین راه برای درک کامل قدرت PrestaSDK، بررسی یک پروژه واقعی است. ماژول WaBulkUpdate که در طول این مستندات به بخش‌هایی از آن اشاره شد، یک مثال کامل و متن‌باز است که با استفاده از همین SDK توسعه داده شده است.
این ماژول از تمام قابلیت‌های کلیدی SDK از جمله موارد زیر استفاده می‌کند:
- نصب‌کننده‌های خودکار برای تب‌ها و جداول
- کنترلرهای مدیریت مبتنی بر AdminController
- مدیریت داده‌ها با BaseModel
- ساختار پنل مدیریت با PanelCore و منوی کناری
توصیه می‌کنیم کد منبع این ماژول را بررسی کنید تا ببینید چگونه مفاهیم توضیح داده شده در این مستندات، در عمل پیاده‌سازی شده‌اند.
- مشاهده ریپازیتوری ماژول WaBulkUpdate در گیت‌هاب
### ۷.۳. مشارکت و گزارش خطا

PrestaSDK یک پروژه متن‌باز است و ما از مشارکت شما استقبال می‌کنیم. اگر با مشکلی مواجه شدید، ایده‌ای برای بهبود داشتید یا می‌خواهید در توسعه آن مشارکت کنید، می‌توانید از طریق ریپازیتوری گیت‌هاب با ما در ارتباط باشید.

- **گزارش خطا یا ارائه پیشنهاد در گیت‌هاب PrestaSDK**

امیدواریم از کار با PrestaSDK لذت ببرید و ماژول‌های قدرتمندی با آن بسازید!