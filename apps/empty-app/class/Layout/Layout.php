<?php

namespace Layout;

class Layout
{

    private $elementFiles;
    private $elementsDir;

    protected $onDisplayBefore;


    protected function __construct()
    {
        $this->elementsDir = APP_ROOT_DIR . "/layout-elements";
    }

    public static function create()
    {
        return new self;
    }


    /**
     * array of elementType => fileName
     */
    public function setElementFiles(array $files)
    {
        $this->elementFiles = $files;
        return $this;
    }

    public function display()
    {
        if (is_callable($this->onDisplayBefore)) {
            call_user_func($this->onDisplayBefore, $this);
        }

        ob_start();
        ?>
        <body>
        <?php

        try {
            // we just prevent the exception from breaking the ob_start
            $this->includeElement('body');
        } catch (\Exception $exception) {
            ?>
            <p>
                Ouch, an error occurred. Please check the logs
            </p>
            <?php
            \Logger::log($exception, "layout.body");
        }
        ?>
        </body>
        <?php
        $body = ob_get_clean();
        ?>


        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?php
                echo ucfirst(WEBSITE_NAME); // todo, need more control...
                ?></title>
            <link rel="stylesheet" href="<?php echo url('/style/style.css'); ?>">
            <?php
            //            $assets = new AssetsList();
            //            LayoutBridge::registerAssets($assets);
            //            $assets->displayList();
            ?>
        </head>
        <?php echo $body; ?>
        </html>
        <?php
    }


    private function includeElement($key)
    {
        $fileName = $this->elementFiles[$key];
        $file = $this->elementsDir . "/" . $fileName;
        require_once $file;
    }
}