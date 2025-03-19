<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the dashboard html page/form generators 
This was introduced in v1,4,2
-
v1.4.2 - Aldo Prinzi - 2025-Mar-19
=====================================================================
*/

function getDashboard(){
    $userData="";
    if (isset($_SESSION["user"]))
        $userData=$_SESSION["user"];
    if (empty($userData))
        return;
    $db=new Database();
    $userData=$db->getUserByApiKey($userData["apikey"]);
    $content='
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@latest/dist/chartjs-plugin-zoom.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2/dist/chartjs-plugin-datalabels.min.js"></script>

        <script>
            $(document).ready(function () {
                $("#userCodesTable").DataTable({"paging": true, "ordering": true, "info": true});
            });
        </script>
        <div id="modal" class="modal hidden appear">
            <form id="changePassForm" class="auth-form" action="_pls_fnc_register" method="post">
                <div class="modal-header"><span id="modalTitle">TITOLO</span><span class="modal-closer" onclick="closemodal()">&times;</span>
                </div>
                <div class="modal-content">
                    <div class="form-group"> <label for="password">Label 1</label>
                        <input id="modalData1" style="width:95%" type="text" class="input-text" name="input1" value="">
                    </div>
                    <div class="form-group"> <label for="password_confirm">Label 2</label>
                        <input id="modalData2" style="width:95%" type="text" class="input-text" name="input2" value="">
                    </div> 
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Change Password">
                </div>
            </form> 
        </div>
        <div class="form-group"><label>Customer\'s List</label><div class="userTabLinks">
            <table id="userCodesTable" class="display"><thead><tr><th rowspan=2>Name</th><th rowspan=2>E-mail</th><th rowspan=2>Status</th><th colspan=2>Links</th></tr><tr><th>Used</th><th>Max</th></tr></thead><tbody>
    ';
    $db = new Database();
    $result = $db->getCustomersData();

    // Costruzione della tabella HTML
    foreach ($result as $row) {
        if ($row['adm']!=0)
            $actbtn="&nbsp;";
        else {
            $actbtn="<button class='btn btn-small ";
            if ($row['verified']!=0){
                if ($row['active']!=0)
                    $actbtn.="btn-secondary' onclick='openmodal(\"modal\",\"Deactivate\",".$row["id"].",\"".$row['descr']."\")'>DEAC";
                else
                    $actbtn.="btn-warning' onclick='openmodal(\"modal\",\"Activate\",".$row["id"].",\"".$row['descr']."\")'>ACTV";
            } else {
                $actbtn.="btn-primary' onclick='openmodal(\"modal\",\"Delete\",".$row["id"].",\"".$row['descr']."\")'>DEL";
            }
            $actbtn.="</button>";
        }
        $content.="<tr>
        <td>" . $row['descr'] . "</td>
        <td>" . $row['email'] . "</td>
        <td>" . $actbtn ."</td>
        <td align='right'>" . $row["used_links"]."</td><td align='right'>";
        if ($row['adm']!=0)
            $content.="&nbsp;";
        else
            $content.= $row['max_links'];
        $content.="</td></tr>";
    }
    $content.='</tbody></table></div></div>';

    // Dati per Grafico 1: Numero di click e utenti unici per giorno (escludendo crawler)
    $clicks_per_day = [];
    $users_per_day = [];
    $devices = ['android' => 0, 'iphone' => 0, 'pc' => 0, 'mac' => 0];
    
    $logs=$db->getSitecallsLog();

    // Inizio della sezione HTML
    $content.="
    <section class='accordion'><div class='tab'><input type='radio' name='accordion-1' id='rd1'>
        <label for='rd1' class='tab__label'>".lng("call_log")."<button class='btn btn-secondary btn-small' onclick='downloadCSV()'>".lng("download-data")."</button></label><div class='tab__content'>
            <p><table id='callstable' cellpadding='0' cellspacing='0' class='table table-striped table-bordered table-hover'>
            <tr><th>".lng("date")."</th><th>IP</th><th>".lng("city")."</th><th style='display:none'>".lng("region")."</th><th>Country</th><th>".lng("referer")."</th><th>".lng("device")."</th><th>OS</th></tr>
            <tbody>";

    // Processa ogni riga della tabella calls_log
    foreach ($logs as $log) {
        $call_date = $log['call_date'];
        $call_log = $log['call_log'];

        // Split delle chiamate (separate da ;)
        $calls = explode(';', $call_log);

        usort($calls, function($a, $b) {
            // Estrai la data e l'ora dai dettagli (primo campo separato da virgola)
            $dateA = explode(',', trim($a))[0];
            $dateB = explode(',', trim($b))[0];
            
            // Converti le date in timestamp per il confronto
            $timestampA = strtotime($dateA);
            $timestampB = strtotime($dateB);
            
            // Ordine discendente: $b rispetto a $a
            return $timestampB - $timestampA;
        });

        $daily_users = []; // Utenti unici per giorno
        $clicks_per_country = [];

        foreach ($calls as $call) {
            if (empty(trim($call))) continue; // Salta righe vuote

            // Split dei dettagli della chiamata (separate da ,)
            $details = explode(',', $call);
            if (count($details) < 10) continue; // Assicura che ci siano abbastanza campi

            // Estrai i campi
            $time = $details[0];
            $ip = $details[1];
            $city = $details[2];
            $region = $details[3];
            $country = $details[4];
            $referer =strpos($details[6],"_pls_fnc")===false?$details[6]:"[int]";
            $referer =stripos($referer,"flu.lu")!==false?"[int]":$referer;
            $device = $details[7];
            if(stripos($referer,"google.")!==false){
                $device = "GOOGLE";
            }
            if(stripos($referer,"github.")!==false){
                $device = "GITHUB";
            }
            if(stripos($referer,"t.co")!==false || stripos($referer,"twclid")!==false){
                $device = "TWITTER";
            }
            if(stripos($referer,"lnkd.in")!==false || stripos($referer,"linkedin.")!==false){
                $device = "LINKED_IN";
            }
            $os = $details[8];
            $unique_id = $details[9];

            // Costruisci la data completa
            $datetime = new DateTime("$call_date $time");

            // Aggiungi riga alla tabella
            $content.="<tr><td class='tdcont'>".$datetime->format("d-m H:i")."</td><td class='tdcont'>$ip</td><td class='tdcont'>$city</td><td style='display:none'>$region</td><td class='tdcont'>$country</td><td class='tdcont'>$referer</td><td class='tdcont'>$device</td><td class='tdcont'>$os</td></tr>";
            // Salta i bot/crawler
            if ($details[6] === '[bot]' || $details[7] === 'bot') 
                continue;

            // SE BOT NON VIENE CALCOLATO QUI:
            // Incrementa il conteggio dei click per giorno
            if (!isset($clicks_per_day[$call_date])) {
                $clicks_per_day[$call_date] = 0;
            }
            $clicks_per_day[$call_date]++;

            // Aggiungi l'utente unico (basato su unique_id)
            $unique_id = $details[9];
            if (!isset($daily_users[$call_date])) {
                $daily_users[$call_date] = [];
            }
            if (!in_array($unique_id, $daily_users[$call_date])) {
                $daily_users[$call_date][] = $unique_id;
            }

            // Conteggio per device
            $device = strtolower($details[7]);
            $os = strtolower($details[8]);
            if ($device === 'phone') {
                if (strpos($os, 'android') !== false) {
                    $devices['android']++;
                } elseif (strpos($os, 'ios') !== false) {
                    $devices['iphone']++;
                }
            } elseif ($device === 'pc') {
                if (strpos($os, 'macos') !== false) {
                    $devices['mac']++;
                } else {
                    $devices['pc']++;
                }
            }
            // conteggio per paese  
            if (!isset($clicks_per_country[$country])) {
                $clicks_per_country[$country] = 0;
            }
            $clicks_per_country[$country]++;
        }
        // Conta utenti unici per giorno
        $users_per_day[$call_date] = count($daily_users[$call_date]);
    }

    $content.="
            </tbody></table></p></div>
            <div class='tab'><input type='radio' name='accordion-1' id='rd2'><label for='rd2' class='tab__close'>Close ×</label></div>
        </div>
    </section>
    ";

    // Prepara i dati per Chart.js
    $clicks_labels = array_keys($clicks_per_day);
    $clicks_data = array_values($clicks_per_day);
    $users_data = array_values($users_per_day);

    // Dati per Grafico 2: Totale chiamate per device
    $devices_labels = ['Android', 'iPhone', 'PC', 'Mac'];
    $devices_data = array_values($devices);

    $content.="
    <style>
        .chart-container {max-width: 97%;width: 90%;margin-top: 20px;}
        .full-width-chart {width: 100%;height: 450px;}
        .side-by-side {display: flex;justify-content: space-between;width: 100%; margin-top: 20px;}
        .half-width-chart {width: 28%; height: 300px;}
    </style>
    <div class='chart-container'>
        <div class='full-width-chart'><canvas id='clicksUsersChart'></canvas></div>
        <div class='side-by-side'>
            <div class='half-width-chart'><canvas id='deviceChart'></canvas></div>
            <div class='half-width-chart'><canvas id='countryChart'></canvas></div>
        </div>
    </div>
    <script>
        Chart.register(ChartDataLabels);        
        const clicksUsersCtx = document.getElementById('clicksUsersChart').getContext('2d');
        new Chart(clicksUsersCtx, {
            type: 'line', 
            data: {
                labels: ".json_encode($clicks_labels).",
                datasets: [
                    {
                        label: 'Clicks',
                        data:".json_encode($clicks_data).",
                        borderColor: '#237093',
                        backgroundColor: 'rgba(35, 112, 147, 0.2)',
                        fill: true, tension: 0.4 
                    },
                    {
                        label: '".lng("users")."',
                        data:".json_encode($users_data).",
                        borderColor: '#93A0FF',
                        backgroundColor: 'rgba(147, 160, 255, 0.2)',
                        fill: true, tension: 0.4 
                    }
                ]
            },
            options: {
                plugins: { datalabels: {display: false }},
                scales: {
                    x: {type: 'time',time: {unit: 'day',displayFormats: {day: 'eee dd-MM'},tooltipFormat: 'eee dd-MM-yy'},title: {display: false}},
                    y: {beginAtZero: true, title: {display: false}}
                }
            }
        });
        const deviceCtx = document.getElementById('deviceChart').getContext('2d');
        new Chart(deviceCtx, {
            type: 'pie',
            data: {
                labels: ".json_encode($devices_labels).",
                datasets: [{data: ".json_encode($devices_data).",backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']}]
            },
            options: {
                plugins: {
                    legend: {display: false},
                    datalabels: {display: true,color: '#fff',font: {weight: 'bold',size: 16},
                        formatter: (value, context) => {return context.chart.data.labels[context.dataIndex];},
                        textAlign: 'center'
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const value = context.raw; 
                                return `Click: $"."{value}`; 
                            }
                        }
                    }
                }
            }
        });
        const countryCtx = document.getElementById('countryChart').getContext('2d');
        new Chart(countryCtx, {
            type: 'pie',
            data: {
                labels: ".json_encode(array_keys($clicks_per_country)).",
                datasets: [{data: ".json_encode(array_values($clicks_per_country)).",backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF','#FF9F40', '#C9CBCF', '#7BC043', '#F4A261', '#E76F51'
                    ] 
                }]
            },
            options: {
                plugins: {
                    legend: {display: false},
                    datalabels: {display: true, color: '#fff',font: {weight: 'bold',size: 16},
                        formatter: (value, context) => {return context.chart.data.labels[context.dataIndex];},
                        anchor: 'center', align: 'center' 
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {const value = context.raw; return `Click: $"."{value}`;}
                        }
                    }
                }
            }
        });
        function downloadCSV() {
            const table = document.getElementById('callstable');
            const rows = table.querySelectorAll('tr');
            let csvContent = [];
            const headers = [];
            const headerCells = rows[0].querySelectorAll('th');
            headerCells.forEach(header => {
                headers.push(header.textContent.trim());
            });
            csvContent.push(headers.join(','));
            for (let i = 1; i < rows.length; i++) {
                const row = [];
                const cells = rows[i].querySelectorAll('td');
                cells.forEach(cell => {
                    row.push(cell.textContent.trim());
                });
                csvContent.push(row.join(','));
            }
            const csvString = csvContent.join('\\n');
            const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'call_log_' + new Date().toISOString().slice(0,10) + '.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
    ";
    return $content;
}


