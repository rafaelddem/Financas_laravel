<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Tasks\PaymentMethod\LoadPage;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        return (new LoadPage)->run($id, "");
    }

    public function store(PaymentMethodRequest $request)
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethod->name = $request->name;
        $paymentMethod->active = boolval($request->active);
        $paymentMethod->save();

        $message = 'Registro criado com sucesso';

        return (new LoadPage)->run(0, $message);
    }

    public function update(PaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::find($request->id);
        $paymentMethod->name = $request->name;
        $paymentMethod->active = boolval($request->active);
        $paymentMethod->update();

        $message = 'Registro atualizado com sucesso';

        return (new LoadPage)->run(0, $message);
    }

    public function destroy(int $id)
    {
        $paymentMethod = PaymentMethod::find($id);
        $paymentMethod->delete($id);

        $message = 'Registro excluído com sucesso';

        return (new LoadPage)->run(0, $message);
    }
}
