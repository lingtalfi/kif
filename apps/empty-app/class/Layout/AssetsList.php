<?php


namespace Layout;

use Bat\StringTool;


/**
 * The AssetsList helps registering assets.
 *
 * Library dependencies resolution is left to the user.
 *
 * This class ultimately displays the assets in the order in which they are called.
 *
 * An asset will only be displayed once (i.e. this class will ignore duplicates).
 *
 * Detection of duplicate assets is based solely on the url path of the asset.
 *
 * Personal notes:
 * Therefore, it's a good idea to agree on which url to call;
 * using internal url (starting with / as opposed to those starting with http) can be specially useful
 * for the duplicate problem.
 * Also, considering creating a generic /libs/jquery.js file might help in order to resolve
 * the problem of the same library called with different version numbers.
 * I personally tend to not use libraries at all when possible, so this problem is not my priority
 * right now, just wanted to share my thoughts about it...
 *
 */
class AssetsList
{

    /**
     * array of url => item.
     * Each item is an array containing the following:
     *      - 0: assetType=js|css
     *      - 1: html attributes=array|null(default)   (StringTool::htmlAttributes notation)
     *
     * Note: using the url as the key IS the "avoid duplicates" mechanism implementation
     */
    private $list;


    public function __construct()
    {
        $this->list = [];
    }

    public function css($url, array $htmlAttributes = null)
    {
        $this->list[$url] = ['css', $htmlAttributes];
        return $this;
    }

    public function js($url, array $htmlAttributes = null)
    {
        $this->list[$url] = ['js', $htmlAttributes];
        return $this;
    }

    public function displayList()
    {
        foreach ($this->list as $url => $item) {
            $s = (null === $item[1]) ? '' : StringTool::htmlAttributes($item[1]);
            if ('css' === $item[0]) {
                ?>
                <link rel="stylesheet" href="<?php echo htmlspecialchars($url); ?>" <?php echo $s; ?>>
                <?php
            } else {
                ?>
                <script src="<?php echo htmlspecialchars($url); ?>" <?php echo $s; ?>></script>
                <?php
            }
        }
    }

}