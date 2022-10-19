<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    protected $site;

    public function __construct()
    {
        $this->site = site();
    }

    /**
     * Show the home page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $localeName
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function home(Request $request, string $localeName = null)
    {
        if (isset($localeName)) {
            if (Locale::isDefault($localeName)) {
                return redirect()->route('home');
            }

            $this->site->setLocale($localeName);
        }

        $views = [
            "{$this->site->locale->system_name}/home",
            "home",
        ];

        foreach ($views as $view) {
            if (view()->exists($view)) {
                return response()->view($view, ['site' => $this->site]);
            }
        }

        if ($home = $this->site->entries()->home()->first()) {
            return response()->view($home->view(), ['site' => $this->site, 'entry' => $home]);
        }

        abort(404);
    }

    /**
     * Show a specified entry
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $params
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|void
     */
    public function entry(Request $request, ...$params)
    {
        switch (count($params)) {
            case 1:
                $entryName = $params[0];
                break;

            default:
                $this->site->setLocale($localeName = $params[0]);
                $entryName = $params[1];
                break;
        }

        if ($entry = $this->site->entry($entryName)) {
            if (isset($localeName) && Locale::isDefault($localeName)) {
                return redirect()->to($entry->url);
            }

            if ($view = $entry->view()) {
                return response()->view($view, ['site' => $this->site, 'entry' => $entry]);
            }
        }

        abort(404);
    }

    /**
     * Show a specified taxonomy
     *
     * @param Request $request
     * @param ...$params
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|void
     */
    public function taxonomy(Request $request, ...$params)
    {
        switch (count($params)) {
            case 1:
                $taxonomyName = $params[0];
                break;

            default:
                $this->site->setLocale($localeName = $params[0]);
                $taxonomyName = $params[1];
                break;
        }

        if ($taxonomy = $this->site->taxonomy($taxonomyName)) {
            if (isset($localeName) && Locale::isDefault($localeName)) {
                return redirect()->to($taxonomy->url);
            }

            if ($view = $taxonomy->view()) {
                return response()->view($view, ['site' => $this->site, 'taxonomy' => $taxonomy]);
            }
        }

        abort(404);
    }
}
