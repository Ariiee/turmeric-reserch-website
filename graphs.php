<?php
$page_title = "Graphs & Statistics";
include 'db.php';

// Self-healing database check for country baseline samples (Sri Lanka, Bangladesh, Iran)
$baselines = [
    [
        'sample_id' => 'SL-01',
        'sample_type' => 'Raw',
        'location' => 'Sri Lanka Literature',
        'country' => 'Sri Lanka',
        'ba' => 0.0, 'br' => 0.0, 'ca' => 0.0, 'co' => 0.0, 'cr' => 93.5, 'fe' => 340.5, 'k_value' => 0.0, 'na_value' => 0.0, 'rb' => 0.0, 'sc' => 0.0, 'sm' => 0.0, 'zn' => 13.15
    ],
    [
        'sample_id' => 'BD-01',
        'sample_type' => 'Raw',
        'location' => 'Bangladesh Literature',
        'country' => 'Bangladesh',
        'ba' => 0.0, 'br' => 3.67, 'ca' => 0.244, 'co' => 0.072, 'cr' => 0.45, 'fe' => 251.0, 'k_value' => 2.28, 'na_value' => 154.0, 'rb' => 0.0, 'sc' => 0.113, 'sm' => 0.0, 'zn' => 13.1
    ],
    [
        'sample_id' => 'IR-01',
        'sample_type' => 'Raw',
        'location' => 'Iran Literature',
        'country' => 'Iran',
        'ba' => 0.0, 'br' => 38.3, 'ca' => 0.0, 'co' => 0.0, 'cr' => 0.0, 'fe' => 0.0, 'k_value' => 2.89, 'na_value' => 331.0, 'rb' => 0.0, 'sc' => 0.0, 'sm' => 0.0, 'zn' => 0.0
    ]
];

foreach ($baselines as $base) {
    $check_res = mysqli_query($conn, "SELECT id FROM turmeric_samples WHERE sample_id = '" . mysqli_real_escape_string($conn, $base['sample_id']) . "'");
    if (mysqli_num_rows($check_res) == 0) {
        $query = "INSERT INTO turmeric_samples (sample_id, sample_type, location, country, ba, br, ca, co, cr, fe, k_value, na_value, rb, sc, sm, zn) VALUES (
            '" . mysqli_real_escape_string($conn, $base['sample_id']) . "',
            '" . mysqli_real_escape_string($conn, $base['sample_type']) . "',
            '" . mysqli_real_escape_string($conn, $base['location']) . "',
            '" . mysqli_real_escape_string($conn, $base['country']) . "',
            {$base['ba']}, {$base['br']}, {$base['ca']}, {$base['co']}, {$base['cr']}, {$base['fe']}, {$base['k_value']}, {$base['na_value']}, {$base['rb']}, {$base['sc']}, {$base['sm']}, {$base['zn']}
        )";
        mysqli_query($conn, $query);
    }
}

include 'header.php';

// Fetch Dynamic Statistics
$comparison_elements = [
    ['key' => 'ba', 'label' => 'Ba'],
    ['key' => 'br', 'label' => 'Br'],
    ['key' => 'ca', 'label' => 'Ca'],
    ['key' => 'co', 'label' => 'Co'],
    ['key' => 'cr', 'label' => 'Cr'],
    ['key' => 'fe', 'label' => 'Fe'],
    ['key' => 'k_value', 'label' => 'K'],
    ['key' => 'na_value', 'label' => 'Na'],
    ['key' => 'rb', 'label' => 'Rb'],
    ['key' => 'sc', 'label' => 'Sc'],
    ['key' => 'sm', 'label' => 'Sm'],
    ['key' => 'zn', 'label' => 'Zn']
];

// Country comparison averages
$country_select = ['country', 'COUNT(*) as sample_count'];
foreach ($comparison_elements as $element) {
    $country_select[] = "AVG({$element['key']}) as avg_{$element['key']}";
}
$country_stats_res = mysqli_query($conn, "SELECT " . implode(', ', $country_select) . " FROM turmeric_samples GROUP BY country ORDER BY country ASC");
$country_stats = [];
while ($row = mysqli_fetch_assoc($country_stats_res)) {
    $country_stats[$row['country']] = $row;
}

// Fetch all samples for Chart.js
$sample_select = ['sample_id', 'country'];
foreach ($comparison_elements as $element) {
    $sample_select[] = $element['key'];
}
$query = "SELECT " . implode(', ', $sample_select) . " FROM turmeric_samples ORDER BY sample_id ASC";
$result = mysqli_query($conn, $query);
$samples_array = [];
while ($row = mysqli_fetch_assoc($result)) {
    $samples_array[] = $row;
}
?>

<div class="section-header">
    <div class="section-title">
        <h2>Analytics Dashboard</h2>
        <p>Interactive and literature-based element concentration profile comparison</p>
    </div>
</div>

<!-- 1. Literature & Database Comparison Table -->
<?php
$lit_file = __DIR__ . '/data/turmeric_elements.json';
if (file_exists($lit_file)) {
    $lit_json = json_decode(file_get_contents($lit_file), true);
    if (!empty($lit_json['elements'])) {
        
        // helper to map element name to DB key
        function mapElementToKey($name) {
            $n = strtolower(trim($name));
            if (strpos($n, 'ba') === 0) return 'ba';
            if (strpos($n, 'br') === 0) return 'br';
            if (strpos($n, 'ca') === 0) return 'ca';
            if (strpos($n, 'co') === 0) return 'co';
            if (strpos($n, 'cr') === 0) return 'cr';
            if (strpos($n, 'fe') === 0) return 'fe';
            if (strpos($n, 'k') === 0) return 'k_value';
            if (strpos($n, 'na') === 0) return 'na_value';
            if (strpos($n, 'rb') === 0) return 'rb';
            if (strpos($n, 'sc') === 0) return 'sc';
            if (strpos($n, 'sm') === 0) return 'sm';
            if (strpos($n, 'zn') === 0) return 'zn';
            return null;
        }

        $db_countries = array_keys($country_stats);
        $db_countries = array_filter($db_countries, function($c) {
            return !empty($c) && strtolower($c) !== 'unknown';
        });

        echo "<div class=\"chart-card\" style=\"margin-bottom:24px; min-height:auto;\">\n";
        echo "<div class=\"chart-card-title\">Table: Literature Comparison (Table 5) & Database Averages\n";
        echo "<span>INAA Research Baseline & Dynamic Data</span>\n";
        echo "</div>\n";
        echo "<div class=\"table-wrapper\" style=\"padding:12px; overflow-x:auto;\">\n";
        echo "<table style=\"width:100%; border-collapse:collapse;\">\n<thead><tr>\n";
        echo "<th>Element</th>\n";
        echo "<th>India [19]</th>\n";
        echo "<th>India [50]</th>\n";
        echo "<th>Sri Lanka [52]</th>\n";
        echo "<th>Bangladesh [25]</th>\n";
        echo "<th>Iran [51]</th>\n";
        
        // Render DB Columns
        foreach ($db_countries as $c) {
            echo "<th style=\"color: var(--primary); font-weight:700;\">" . htmlspecialchars($c) . " (DB Avg)</th>\n";
        }
        
        echo "<th>Raw turmeric (Table 3)</th>\n";
        echo "<th>DDI± (mg/d) [53,54]</th>\n";
        echo "<th>Branded turmeric (Table 4)</th>\n";
        echo "<th>DDI± (mg/d) [53,54]</th>\n";
        echo "<th>Safe limit by WHO/FAO</th>\n";
        echo "</tr></thead>\n<tbody>\n";

        foreach ($lit_json['elements'] as $el) {
            $dbKey = mapElementToKey($el['element']);
            $is_wt = (strpos(strtolower($el['element']), 'wt%') !== false);

            echo '<tr>';
            echo '<td style="font-weight:600;">' . htmlspecialchars($el['element']) . '</td>';
            echo '<td>' . htmlspecialchars($el['india_19']) . '</td>';
            echo '<td>' . htmlspecialchars($el['india_50']) . '</td>';
            echo '<td>' . htmlspecialchars($el['sri_lanka_52']) . '</td>';
            echo '<td>' . htmlspecialchars($el['bangladesh_25']) . '</td>';
            echo '<td>' . htmlspecialchars($el['iran_51']) . '</td>';
            
            // Database dynamic values
            foreach ($db_countries as $c) {
                $cStats = $country_stats[$c] ?? null;
                $cVal = 'NA';
                if ($cStats && $dbKey && isset($cStats['avg_' . $dbKey])) {
                    $val = $cStats['avg_' . $dbKey];
                    // Skip if value is zero AND the country doesn't actually measure this element in literature
                    // But to keep it simple, if avg is 0.0, we can render "NA" or "0.00"
                    if ($val !== null && $cStats['sample_count'] > 0) {
                        if ($val == 0 && ($c == 'Sri Lanka' || $c == 'Bangladesh' || $c == 'Iran')) {
                            // These countries only have subset values in literature base
                            $cVal = 'NA';
                        } else {
                            $cVal = $is_wt ? number_format($val, 4) : number_format($val, 2);
                        }
                    }
                }
                echo '<td style="color: var(--primary); font-weight:600;">' . $cVal . '</td>';
            }
            
            echo '<td>' . htmlspecialchars($el['raw']) . '</td>';
            echo '<td>' . htmlspecialchars($el['ddi']) . '</td>';
            echo '<td>' . htmlspecialchars($el['branded']) . '</td>';
            echo '<td>' . htmlspecialchars($el['branded_ddi']) . '</td>';
            echo '<td>' . htmlspecialchars($el['safe_limit']) . '</td>';
            echo "</tr>\n";
        }

        echo "</tbody></table>\n</div></div>\n";
    }
}
?>

<!-- 2. Dynamic Overall Quality Comparison & Scorecard -->
<?php
$db_countries = array_keys($country_stats);
$db_countries = array_filter($db_countries, function($c) {
    return !empty($c) && strtolower($c) !== 'unknown';
});

if (count($db_countries) > 1) {
    $nutrients = ['fe', 'zn', 'ca', 'k_value'];
    $contaminants = ['cr', 'ba', 'br', 'co', 'na_value', 'rb', 'sc', 'sm'];

    $element_labels = [
        'ba' => 'Barium (Ba)',
        'br' => 'Bromine (Br)',
        'ca' => 'Calcium (Ca)',
        'co' => 'Cobalt (Co)',
        'cr' => 'Chromium (Cr)',
        'fe' => 'Iron (Fe)',
        'k_value' => 'Potassium (K)',
        'na_value' => 'Sodium (Na)',
        'rb' => 'Rubidium (Rb)',
        'sc' => 'Scandium (Sc)',
        'sm' => 'Samarium (Sm)',
        'zn' => 'Zinc (Zn)'
    ];

    $db_limits = [
        'cr' => ['val' => 2.3, 'unit' => 'mg/kg', 'label' => 'Chromium (Cr)'],
        'br' => ['val' => 4.0, 'unit' => 'mg/kg', 'label' => 'Bromine (Br)'],
        'zn' => ['val' => 100.0, 'unit' => 'mg/kg', 'label' => 'Zinc (Zn)'],
        'co' => ['val' => 3.5, 'unit' => 'mg/kg', 'label' => 'Cobalt (Co)'],
    ];

    $comparison_data = [];
    foreach ($db_countries as $country) {
        $comparison_data[$country] = [
            'nutrients_won' => [],
            'contaminants_won' => [],
            'score' => 0,
            'warnings' => [],
            'sample_count' => $country_stats[$country]['sample_count']
        ];
    }

    foreach (array_merge($nutrients, $contaminants) as $el) {
        $best_val = null;
        $best_country = null;
        $is_nutrient = in_array($el, $nutrients);
        
        foreach ($db_countries as $country) {
            $val = $country_stats[$country]['avg_' . $el] ?? null;
            if ($val === null || $val == 0 || $country_stats[$country]['sample_count'] == 0) continue;
            
            // Check safe limits
            if (isset($db_limits[$el])) {
                if ($val > $db_limits[$el]['val']) {
                    $comparison_data[$country]['warnings'][] = [
                        'element' => $db_limits[$el]['label'],
                        'val' => $val,
                        'limit' => $db_limits[$el]['val'],
                        'unit' => $db_limits[$el]['unit']
                    ];
                }
            }
            
            if ($best_val === null) {
                $best_val = $val;
                $best_country = $country;
            } else {
                if ($is_nutrient) {
                    if ($val > $best_val) {
                        $best_val = $val;
                        $best_country = $country;
                    }
                } else {
                    if ($val < $best_val) {
                        $best_val = $val;
                        $best_country = $country;
                    }
                }
            }
        }
        
        if ($best_country !== null) {
            if ($is_nutrient) {
                $comparison_data[$best_country]['nutrients_won'][] = $element_labels[$el];
                $comparison_data[$best_country]['score'] += 2;
            } else {
                $comparison_data[$best_country]['contaminants_won'][] = $element_labels[$el];
                $comparison_data[$best_country]['score'] += 3; // Safety (low contaminants) carries higher weight
            }
        }
    }

    // Sort countries by quality score descending
    uasort($comparison_data, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    $rank = 1;
    $leaderboard = [];
    foreach ($comparison_data as $country => $data) {
        $leaderboard[] = [
            'country' => $country,
            'score' => $data['score'],
            'rank' => $rank++
        ];
    }
    $winner = $leaderboard[0]['country'];
    
    // Generate description analysis
    $analysis_text = "According to the dynamically calculated <strong>Multi-Elemental Quality Index</strong>, <strong>" . htmlspecialchars($winner) . "</strong>'s turmeric is rated superior overall among the " . count($db_countries) . " countries. ";
    $analysis_text .= "Score calculations award points for higher trace nutrients (Fe, Zn, Ca, K) and lower heavy metal contamination (Cr, Ba, Br, Co, Na, Rb, Sc, Sm). ";
    
    if (count($comparison_data[$winner]['warnings']) > 0) {
        $analysis_text .= "Note that " . htmlspecialchars($winner) . " still exhibits safety threshold alerts on certain elements.";
    } else {
        $analysis_text .= htmlspecialchars($winner) . " fully satisfies all standard WHO/FAO safety limit parameters in the database.";
    }
    ?>
    <div class="chart-card" style="margin-bottom: 24px; min-height: auto;">
        <div class="chart-card-title">
            Dynamic Quality & Safety Scorecard Comparison
            <span>Overall Multi-Country Rankings (Live DB Averages)</span>
        </div>
        
        <div style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: var(--radius-md); padding: 18px; margin-bottom: 20px; font-size: 0.95rem; line-height: 1.6; display: flex; align-items: flex-start; gap: 12px;">
            <div style="font-size: 1.6rem; line-height: 1;">🏆</div>
            <div>
                <p style="margin: 0; color: #fff; font-weight: 600; font-family: var(--font-heading); font-size: 1.15rem; margin-bottom: 4px;">Dynamic Quality Verdict: <?php echo htmlspecialchars($winner); ?> is Superior</p>
                <p style="margin: 0; color: var(--text-secondary);"><?php echo $analysis_text; ?></p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <?php foreach ($comparison_data as $country => $data): 
                $cRank = 1;
                foreach ($leaderboard as $l) {
                    if ($l['country'] === $country) $cRank = $l['rank'];
                }
                $isWinner = ($cRank === 1);
                $card_border = $isWinner ? '1px solid rgba(16, 185, 129, 0.35)' : '1px solid var(--border-color)';
                $badge_bg = $isWinner ? 'rgba(16, 185, 129, 0.1)' : 'rgba(255, 255, 255, 0.05)';
                $badge_color = $isWinner ? '#10b981' : 'var(--text-secondary)';
            ?>
                <div style="background: rgba(255,255,255,0.02); border: <?php echo $card_border; ?>; border-radius: var(--radius-md); padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h3 style="font-family: var(--font-heading); font-weight: 700; font-size: 1.25rem; margin: 0; color: #fff;">
                                <?php echo htmlspecialchars($country); ?>
                            </h3>
                            <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; background: <?php echo $badge_bg; ?>; color: <?php echo $badge_color; ?>;">
                                Rank #<?php echo $cRank; ?>
                            </span>
                        </div>
                        
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px;">
                            Based on <strong><?php echo $data['sample_count']; ?></strong> samples in dataset.
                        </div>

                        <!-- Strengths -->
                        <div style="margin-bottom: 12px;">
                            <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: #10b981; display: block; margin-bottom: 6px; letter-spacing: 0.02em;">Safety (Lowest Contaminants)</span>
                            <?php if (count($data['contaminants_won']) > 0): ?>
                                <p style="font-size: 0.85rem; color: #d1fae5; margin: 0; line-height: 1.45;">
                                    <?php echo implode(', ', $data['contaminants_won']); ?>
                                </p>
                            <?php else: ?>
                                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0; font-style: italic;">No contaminant leadership categories.</p>
                            <?php endif; ?>
                        </div>

                        <div style="margin-bottom: 12px;">
                            <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: #3b82f6; display: block; margin-bottom: 6px; letter-spacing: 0.02em;">Nutrients (Highest Content)</span>
                            <?php if (count($data['nutrients_won']) > 0): ?>
                                <p style="font-size: 0.85rem; color: #dbeafe; margin: 0; line-height: 1.45;">
                                    <?php echo implode(', ', $data['nutrients_won']); ?>
                                </p>
                            <?php else: ?>
                                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0; font-style: italic;">No nutritional leadership categories.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Safety Alerts / Warnings -->
                    <?php if (count($data['warnings']) > 0): ?>
                        <div style="margin-top: 15px; padding: 12px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: var(--radius-sm);">
                            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; color: var(--danger); display: block; margin-bottom: 4px;">⚠️ Safety Threshold Alerts</span>
                            <ul style="margin: 0; padding-left: 14px; font-size: 0.8rem; color: #fca5a5; line-height: 1.4;">
                                <?php foreach ($data['warnings'] as $warning): ?>
                                    <li><?php echo $warning['element']; ?> average is <?php echo number_format($warning['val'], 2); ?> <?php echo $warning['unit']; ?> (Safe: &lt;= <?php echo $warning['limit']; ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div style="margin-top: 15px; padding: 10px; background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.15); border-radius: var(--radius-sm); font-size: 0.8rem; color: #a7f3d0; text-align: center;">
                            ✓ All elements comply with safe thresholds
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php } ?>

<!-- 3. Element-by-Element Comparison Section (Dynamic UI) -->
<div class="chart-card" style="margin-bottom: 24px; min-height: auto;">
    <div class="chart-card-title">
        Element-by-Element Comparison
        <span>Compare which country's turmeric is better in terms of a specific element</span>
    </div>
    
    <div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <label for="elementSelector" style="font-family: var(--font-heading); font-weight: 600; color: var(--text-secondary); font-size: 0.95rem;">Select Element for Comparison:</label>
        <select id="elementSelector" class="form-control" style="max-width: 250px; background: var(--bg-input); border: 1px solid var(--border-color); color: #fff; padding: 8px 12px; border-radius: var(--radius-sm);">
            <?php foreach ($comparison_elements as $el): ?>
                <option value="<?php echo htmlspecialchars($el['key']); ?>"><?php echo htmlspecialchars($el['label']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Live Verdict box -->
    <div id="elementComparisonVerdict" style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: var(--radius-md); padding: 18px; margin-bottom: 20px; font-size: 0.95rem; line-height: 1.6; display: flex; align-items: flex-start; gap: 12px;">
        <div style="font-size: 1.5rem; line-height: 1;">📊</div>
        <div id="elementVerdictText" style="color: var(--text-secondary); width: 100%;">
            Select an element above to see the relative ranking and quality comparison of all countries in the database.
        </div>
    </div>

    <!-- Element chart container -->
    <div class="chart-container" style="height: 380px; margin-top: 10px;">
        <canvas id="elementComparisonChart"></canvas>
    </div>
</div>

<!-- 4. Combined Comparison Chart -->
<div class="charts-dashboard-grid">
    <div class="chart-card">
        <div class="chart-card-title">
            All Element Concentration Comparison by Country (Live Data Averages)
            <span>Concentration in mg/kg (or wt% where indicated)</span>
        </div>
        <div class="chart-container">
            <canvas id="combinedComparisonChart"></canvas>
        </div>
    </div>
</div>

<!-- Inject PHP array into Javascript for Chart.js initialization -->
<script>
    const dbSamplesData = <?php echo json_encode($samples_array); ?>;
    const countryStatsData = <?php echo json_encode($country_stats); ?>;
    
    // Fire chart rendering script on DOM ready
    window.addEventListener('DOMContentLoaded', () => {
        if (typeof initChartsDashboard === 'function') {
            initChartsDashboard(dbSamplesData);
        }
        if (typeof initElementComparison === 'function') {
            initElementComparison(countryStatsData);
        }
    });
</script>

<?php include 'footer.php'; ?>
