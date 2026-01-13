@extends('layouts.app') 

@section('content')
<div class="container" style="max-width: 1200px; margin-top: 50px;">
    
    <div style="border-bottom: 4px solid #b91c1c; margin-bottom: 20px; padding-bottom: 10px;">
        <h1 style="color: #b91c1c; font-weight: 800; text-transform: uppercase;">
            <i class="fas fa-tasks"></i> Gestion des Livraisons
        </h1>
    </div>

    @if(isset($messageAuto) && $messageAuto)
        <div style="background-color: #eff6ff; color: #1e40af; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #dbeafe;">
            <i class="fas fa-magic"></i> {{ $messageAuto }}
        </div>
    @endif

    @if(session('success'))
        <div style="background-color: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0; display: flex; gap: 20px; align-items: center;">
        <div>
            <label style="font-weight: bold; margin-right: 10px;">Type Livraison :</label>
            <select id="filterType" onchange="applyFilters()" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                <option value="all">Tous</option>
                @foreach($typesLivraison as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-weight: bold; cursor: pointer;">
                <input type="checkbox" id="filterEnCours" onchange="applyFilters()"> 
                Afficher uniquement "En cours" / "Sous réserve"
            </label>
        </div>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;">
        <table id="tableCommandes" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #b91c1c; color: white;">
                <tr>
                    <th onclick="sortTable(0)" style="padding: 15px; text-align: left; cursor: pointer;">N° <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(1)" style="padding: 15px; text-align: left; cursor: pointer;">Client <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(2)" style="padding: 15px; text-align: left; cursor: pointer;">Type <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(3)" style="padding: 15px; text-align: center; cursor: pointer;">Date Commande <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(4)" style="padding: 15px; text-align: center; cursor: pointer;">Date Livraison <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(5)" style="padding: 15px; text-align: center; cursor: pointer;">État <i class="fas fa-sort"></i></th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($commandes as $cmd)
                    <tr class="row-item" data-type="{{ $cmd->libelletypelivraison }}" data-etat="{{ $cmd->etatcommande }}" style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; font-weight: bold;">#{{ $cmd->idcommande }}</td>
                        <td style="padding: 15px;">{{ strtoupper($cmd->nom) }} {{ $cmd->prenom }}</td>
                        <td style="padding: 15px;">{{ $cmd->libelletypelivraison }}</td>
                        
                        <td style="padding: 15px; text-align: center;" data-date="{{ \Carbon\Carbon::parse($cmd->date_commande)->timestamp }}">
                            {{ \Carbon\Carbon::parse($cmd->date_commande)->format('d/m/Y') }}
                        </td>
                        
                        <td style="padding: 15px; text-align: center;" data-date="{{ \Carbon\Carbon::parse($cmd->date_livraison)->timestamp }}">
                            {{ \Carbon\Carbon::parse($cmd->date_livraison)->format('d/m/Y') }}
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            @if($cmd->etatcommande == 'Sous réserve')
                                <span style="background: #fff7ed; color: #c2410c; padding: 5px 10px; border-radius: 6px; font-size: 0.9em; font-weight: bold;">
                                    <i class="fas fa-exclamation-triangle"></i> Sous réserve
                                </span>
                                <div style="font-size: 0.75em; color: #666; margin-top: 4px;">
                                    Depuis le {{ \Carbon\Carbon::parse($cmd->datereserve)->format('d/m/Y') }}
                                </div>
                            @else
                                <span style="background: #f3f4f6; color: #374151; padding: 5px 10px; border-radius: 6px; font-size: 0.9em;">
                                    {{ $cmd->etatcommande }}
                                </span>
                            @endif
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            @if($cmd->etatcommande == 'En cours de livraison' || $cmd->etatcommande == 'Sous réserve')
                                <form action="{{ route('siege.etat.update', $cmd->idcommande) }}" method="POST" style="display:flex; gap:5px; justify-content:center; flex-wrap: wrap;">
                                    @csrf
                                    
                                    @if($cmd->etatcommande != 'Sous réserve')
                                    <button type="submit" name="action" value="accepter" style="background-color: #166534; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85em;" title="Valider réception">
                                        <i class="fas fa-check"></i> Reçu
                                    </button>
                                    @endif

                                    @if($cmd->etatcommande == 'Sous réserve')
                                        <button type="submit" name="action" value="accepter" style="background-color: #166534; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85em;" title="Lever la réserve">
                                            <i class="fas fa-check-double"></i> Lever Réserve
                                        </button>
                                    @else
                                        <button type="submit" name="action" value="reserve" style="background-color: #ea580c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85em;" title="Mettre en réserve">
                                            <i class="fas fa-exclamation-circle"></i> Réserve
                                        </button>
                                    @endif

                                    <button type="submit" name="action" value="refuser" style="background-color: #991b1b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85em;" title="Accepter le refus">
                                        <i class="fas fa-undo"></i> Refus
                                    </button>
                                </form>
                            @else
                                <span style="color: #9ca3af; font-size: 0.85em;">Clôturée</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function applyFilters() {
            let typeFilter = document.getElementById('filterType').value;
            let enCoursOnly = document.getElementById('filterEnCours').checked;
            let rows = document.querySelectorAll('.row-item');

            rows.forEach(row => {
                let type = row.getAttribute('data-type');
                let etat = row.getAttribute('data-etat');
                let show = true;

                if (typeFilter !== 'all' && type !== typeFilter) show = false;
                if (enCoursOnly) {
                    if (etat !== 'En cours de livraison' && etat !== 'Sous réserve') show = false;
                }

                row.style.display = show ? '' : 'none';
            });
        }

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("tableCommandes");
            switching = true;
            dir = "asc"; 
            
            while (switching) {
                switching = false;
                rows = table.rows;
                
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    
                    let xVal = x.getAttribute('data-date') ? parseInt(x.getAttribute('data-date')) : x.innerText.toLowerCase();
                    let yVal = y.getAttribute('data-date') ? parseInt(y.getAttribute('data-date')) : y.innerText.toLowerCase();

                    if (!isNaN(parseFloat(xVal)) && isFinite(xVal) && !x.getAttribute('data-date')) {
                        xVal = parseFloat(xVal.replace('#', ''));
                        yVal = parseFloat(yVal.replace('#', ''));
                    }

                    if (dir == "asc") {
                        if (xVal > yVal) { shouldSwitch = true; break; }
                    } else if (dir == "desc") {
                        if (xVal < yVal) { shouldSwitch = true; break; }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++;      
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</div>
@endsection