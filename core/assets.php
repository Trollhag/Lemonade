<?php

namespace Lemonade;

if (!defined("ABSPATH")) {
    die(-1);
}

class Manifest {
    private $manifest;
    public $assets;
    function __construct() {
        try {
            $this->assets = json_decode(file_get_contents(ABSDIST . "/static/assets.json"));
        }
        catch (Exception $e) {
            vueson_debug_log("Failed to load assets json - " . $e->getMessage());            
        }
        if (!is_object($this->assets)) {
            vueson_debug_log("Assets not properly loaded: " . gettype($this->manifest));
        }
    }
}

class Assets {
    protected $registered = [];
    protected $enqueued = [];
    protected $loaded = [];
    function __construct() {        
        $manifest = new Manifest();
        if ($manifest->assets->manifest) {
            $this->enqueue('manifest/js', 'js', $manifest->assets->manifest->js);
        }
        $this->enqueue('vendor/js', 'js', $manifest->assets->vendor->js);
        $this->enqueue('app/js', 'js', $manifest->assets->app->js, ["lemon/js", "routes/js", "vendor/js"]);
        if ($manifest->assets->app->css) {
            $this->enqueue('app/css', 'css', $manifest->assets->app->css);
        }
    }
    public function register($handle, $type, $asset, $deps = []) {
        $this->registered[$handle] = [
            "type"  => $type,
            "asset" => $asset,
            "deps"  => $deps
        ];
    }
    public function enqueue($handle, $type = '', $asset = '', $deps = []) {
        if (!empty($type) && !empty($asset)) {
            $this->register($handle, $type, $asset, $deps);
        }
        if (!in_array($handle, $this->enqueued))
        $this->enqueued[] = $handle;
    }
    public function dequeue($handle) {
        if (($index = array_search($this->enqueued)) !== false) {
            unset($this->enqueued[$index]);
        }
    }
    public function output() {
        foreach ($this->enqueued as $index=>$handle) {
            $asset = $this->registered[$handle];
            if (!empty($asset["deps"])) {
                $deps_loaded = true;
                foreach ($asset["deps"] as $dep) {
                    if (!array_key_exists($dep, $this->registered)) {
                        unset($this->enqueued[$index]);
                        $deps_loaded = false;
                        vueson_debug_log("Dependency asset not registered: " . strval($dep));
                    }
                    else {
                        $this->load($dep);
                    }
                }
            }
            $this->load($handle);
        }
    }
    private function load($handle) {
        if (!in_array($handle, $this->loaded)) {
            $asset = $this->registered[$handle];
            $this->do_output($handle, $asset["type"], $asset["asset"]);
            $this->loaded[] = $handle;
        }
    }
    private function do_output($handle, $type, $asset) {        
        $output = '';
        switch ($type) {
            case 'js':
                $output = "<script id=\"{$handle}\" type=\"text/javascript\" src=\"{$asset}\"></script>";
                break;
            case 'inline_js':
                $output = "<script id=\"{$handle}\" type=\"text/javascript\">\n{$asset}\n</script>";
                break;
            case 'css':
                $output = "<link id=\"{$handle}\" rel=\"stylesheet\" href=\"{$asset}\" />";
                break;
            default: 
                vueson_debug_log("Unknown asset type: {$type}");
                break;
        }
        echo $output;
    }
}
$Lemon->assets = new Assets();

