<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentLink;

class SubscriptionController extends Controller
{
    public function listPayments()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Obtener una lista de PaymentIntents desde Stripe
            $payments = \Stripe\PaymentIntent::all(['limit' => 10]); // Puedes ajustar el límite según tus necesidades

            // Pasar los datos a la vista
            return view('subscription.lista', ['payments' => $payments->data]);

        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error al obtener la lista de pagos: ' . $e->getMessage()]);
        }
    }



    public function createPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Validar los datos del formulario
            $request->validate([
                'amount' => 'required|numeric|min:0.5',
                'description' => 'required|string|max:255',
                'payment_method' => 'required|string',
            ]);

            // Crear el PaymentIntent en Stripe con return_url
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $request->amount * 100, // Convertir a centavos
                'currency' => 'usd',
                'payment_method' => $request->payment_method,
                'description' => $request->description,
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'return_url' => route('subscription.list'), // URL a donde redirigir después del pago
            ]);

            // Redirigir al usuario a una página de éxito
            return response()->json(['url' => route('subscription.list')]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function storeuser(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encriptar la contraseña
        ]);

        // // Opcional: Autenticar al usuario inmediatamente después de registrarse
        // Auth::login($user);

        // Redirigir a la página de dashboard o cualquier otra
        return redirect()->route('subscription.pagar')->with('success', 'Usuario registrado exitosamente');
    }

    public function index()
    {
        $plans = [
            ['name' => 'Mensual', 'price' => 11.15, 'projects' => 10, 'storage' => '5GB', 'features' => ['Email Support', 'Basic Analytics']],
            ['name' => 'Trimestral', 'price' => 33.15, 'projects' => 'Unlimited', 'storage' => 'Unlimited', 'features' => ['Phone & Chat Support', 'Custom Analytics']],
            ['name' => 'Semestral', 'price' => 22.15, 'projects' => 50, 'storage' => '50GB', 'features' => ['Advanced Analytics', 'Enhanced Security']],
            ['name' => 'Anual', 'price' => 22.15, 'projects' => 50, 'storage' => '50GB', 'features' => ['Advanced Analytics', 'Enhanced Security']]
        ];

        return view('subscription.index', compact('plans'));
    }
    public function registrar()
    {
        return view('subscription.registrar');
    }
    public function pagar()
    {
        return view('subscription.pagar');
    }

    public function selectPlan(Request $request, $plan)
    {
        // Si el usuario no está autenticado, guardar el plan en la sesión y redirigir al registro
        if (!Auth::check()) {
            $request->session()->put('selected_plan', $plan);
            return redirect()->route('register')->with('message', 'Please register to continue.');
        }

        // Si el usuario está autenticado, redirigir directamente a la página de pago
        return redirect()->route('payment.page')->with('message', 'You selected the plan: ' . $plan);
    }
}
