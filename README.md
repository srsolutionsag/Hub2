## Installation

### Install Hub2 plugin
Start at your ILIAS root directory 

```bash
mkdir -p Customizing/global/plugins/Services/Cron/CronHook
cd Customizing/global/plugins/Services/Cron/CronHook
git clone https://github.com/studer-raimann/Hub2.git
```

### Install dependencies via composer
```bash
cd Hub2
composer install
```

If you run composer from vagrant box, remember to run it as user `www-data`.
```bash
sudo -u www-data composer install
```

### Dependencies
* [srag/activerecordconfig](https://packagist.org/packages/srag/activerecordconfig)
* [srag/dic](https://packagist.org/packages/srag/dic)
* [srag/removeplugindataconfirm](https://packagist.org/packages/srag/removeplugindataconfirm)

Please use it for further development!

