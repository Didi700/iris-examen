@extends('layouts.guest')

@section('title', 'Vérifier votre email')

@section('heading', 'Vérifiez votre adresse email')

@section('content')
    <div class="text-center">
        <div class="mb-4 text-sm text-gray-600">
            Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. Si vous n'avez pas reçu l'email, nous vous en enverrons un autre avec plaisir.
        </div>

        @if (session('message'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <div class="mt-6 flex items-center justify-center space-x-4">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button 
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Renvoyer l'email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    type="submit"
                    class="px-4 py-2 text-gray-600 hover:text-gray-900"
                >
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
@endsection