<?php declare(strict_types = 1);

namespace Adepta\Proton\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class ProtonController extends BaseController
{
    /**
     * Action to render main Proton template.
     *
     * @return View
    */
    public function index() : View
    {
        return view('proton::index');
    }
}
