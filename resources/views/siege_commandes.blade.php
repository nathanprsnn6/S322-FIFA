@extends('layouts.app') 

@section('content')
<div class="container" style="max-width: 1200px; margin-top: 50px;">
    
    <div style="border-bottom: 4px solid #b91c1c; margin-bottom: 30px; padding-bottom: 10px;">
        <h1 style="color: #b91c1c; font-weight: 800; text-transform: uppercase;">
            <i class="fas fa-shipping-fast"></i> Suivi Qualité - Commandes Express
        </h1>
        <p style="color: #666;">Contrôle des délais de livraison pour le service VIP (Objectif : 3 jours max)</p>
    </div>

    @if($commandes->isEmpty())
        <div style="background-color: #fff1f2; color: #9f1239; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #fecdd3;">
            <i class="fas fa-info-circle"></i> Aucune commande en livraison Express pour le moment.
        </div>
    @else
        <div style="background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #b91c1c; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">N° Commande</th>
                        <th style="padding: 15px; text-align: left;">Client</th>
                        <th style="padding: 15px; text-align: center;">Date Commande</th>
                        <th style="padding: 15px; text-align: center;">Date Livraison Prévue</th>
                        <th style="padding: 15px; text-align: center;">Performance</th>
                        <th style="padding: 15px; text-align: center;">État</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandes as $cmd)
                        @php
                            // Calcul du délai total en jours
                            $dateCmd = \Carbon\Carbon::parse($cmd->date_commande);
                            $dateLiv = \Carbon\Carbon::parse($cmd->date_livraison);
                            $diffJours = $dateCmd->diffInDays($dateLiv);
                        @endphp
                        <tr style="border-bottom: 1px solid #eee; transition: background 0.2s;">
                            <td style="padding: 15px; font-weight: bold;">#{{ $cmd->idcommande }}</td>
                            <td style="padding: 15px;">{{ strtoupper($cmd->nom) }} {{ $cmd->prenom }}</td>
                            
                            <td style="padding: 15px; text-align: center;">
                                {{ $dateCmd->format('d/m/Y') }}
                            </td>
                            
                            <td style="padding: 15px; text-align: center; font-weight: bold; color: #b91c1c;">
                                {{ $dateLiv->format('d/m/Y') }}
                            </td>

                            <td style="padding: 15px; text-align: center;">
                                @if($diffJours <= 1)
                                    <span style="background:#dcfce7; color:#166534; padding: 4px 10px; border-radius: 20px; font-size: 0.85em; font-weight:bold;">
                                        Excellence ({{ $diffJours }} j)
                                    </span>
                                @elseif($diffJours <= 3)
                                    <span style="background:#fff7ed; color:#9a3412; padding: 4px 10px; border-radius: 20px; font-size: 0.85em; font-weight:bold;">
                                        Standard ({{ $diffJours }} j)
                                    </span>
                                @else
                                    {{-- Calcul du dépassement : Durée totale - 3 jours autorisés --}}
                                    <span style="background:#fee2e2; color:#991b1b; padding: 4px 10px; border-radius: 20px; font-size: 0.85em; font-weight:bold;">
                                        Hors délai (+{{ $diffJours - 3 }} j)
                                    </span>
                                @endif
                            </td>

                            <td style="padding: 15px; text-align: center;">
                                <span style="background: #f3f4f6; color: #374151; padding: 5px 10px; border-radius: 6px; font-size: 0.9em;">
                                    {{ $cmd->etatcommande }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection