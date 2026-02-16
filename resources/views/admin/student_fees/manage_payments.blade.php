@extends('layouts.ad')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success mt-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.student_fees.index', $studentFee->id) }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <h1>Gestion des paiements - {{ $studentFee->student->nom }} {{ $studentFee->student->prenom }}</h1>
        <p>Frais : {{ $studentFee->fee->nom }}</p>

        <form action="{{ route('admin.student_fees.update_payments', $studentFee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Mode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            {{-- Colonne lecture seule : type paiement --}}
                            <td>
                                <select name="payments[{{ $payment->id }}][fee_id]" class="form-control">
                                    @foreach($fees as $fee)
                                        <option value="{{ $fee->id }}" {{ $payment->fee_id == $fee->id ? 'selected' : '' }}>
                                            {{ ucfirst($fee->nom) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>


                            <td>
                                <input type="date" name="payments[{{ $payment->id }}][date_paiement]"
                                    value="{{ $payment->date_paiement->format('Y-m-d') }}" class="form-control">
                            </td>

                            <td>
                                <input type="number" name="payments[{{ $payment->id }}][montant]"
                                    value="{{ intval($payment->montant) == $payment->montant ? intval($payment->montant) : $payment->montant }}"
                                    step="0.01" class="form-control">
                            </td>

                            <td>
                                <select name="payments[{{ $payment->id }}][mode_paiement]" class="form-control">
                                    <option value="espèce" {{ $payment->mode_paiement == 'espèce' ? 'selected' : '' }}>Espèce
                                    </option>
                                    <option value="chèque" {{ $payment->mode_paiement == 'chèque' ? 'selected' : '' }}>Chèque
                                    </option>
                                    <option value="virement" {{ $payment->mode_paiement == 'virement' ? 'selected' : '' }}>
                                        Virement</option>
                                    <option value="mobile_money" {{ $payment->mode_paiement == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-dark mt-3">Mettre à jour</button>
        </form>
    </div>
@endsection