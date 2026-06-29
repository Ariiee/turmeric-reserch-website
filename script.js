/**
 * Turmeric Research Portal - Client Side Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Navigation Active State Highlighter
    highlightActiveNavLink();

    // 2. Delete Confirmation Handler
    setupDeleteConfirmations();

    // 3. Form Validation (if forms exist)
    setupFormValidation();
});

/**
 * Automatically marks the navigation item matching the current page as active
 */
function highlightActiveNavLink() {
    const currentPath = window.location.pathname;
    const pageName = currentPath.substring(currentPath.lastIndexOf('/') + 1);
    
    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === pageName || (pageName === '' && linkPage === 'index.php')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

/**
 * Attaches warning prompts to all elements with class 'btn-delete'
 */
function setupDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const sampleId = button.getAttribute('data-sample-id') || 'this sample';
            const confirmed = confirm(`Are you absolutely sure you want to permanently delete sample "${sampleId}"? This action cannot be undone.`);
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Basic client-side validation for sample entry and edit forms
 */
function setupFormValidation() {
    const sampleForm = document.querySelector('.sample-form');
    if (!sampleForm) return;

    sampleForm.addEventListener('submit', (e) => {
        let valid = true;
        const numberInputs = sampleForm.querySelectorAll('input[type="number"], input[step="any"]');
        
        // General elements bounds check
        numberInputs.forEach(input => {
            const val = parseFloat(input.value);
            if (isNaN(val) || val < 0) {
                alert(`Please enter a valid positive number for ${input.previousElementSibling ? input.previousElementSibling.textContent : 'element values'}.`);
                input.focus();
                valid = false;
                e.preventDefault();
                return;
            }
        });

        // Sample ID format check
        const sampleIdInput = sampleForm.querySelector('#sample_id');
        if (sampleIdInput && !/^[A-Za-z0-9\-]+$/.test(sampleIdInput.value)) {
            alert('Sample ID must contain only alphanumeric characters and dashes (e.g. RT-1).');
            sampleIdInput.focus();
            valid = false;
            e.preventDefault();
        }
    });
}

function initChartsDashboard(samplesArray) {
    if (!samplesArray || samplesArray.length === 0) return;

    const countryStats = {};
    const elementColumns = ['ba', 'br', 'ca', 'co', 'cr', 'fe', 'k_value', 'na_value', 'rb', 'sc', 'sm', 'zn'];
    const elementLabels = {
        ba: 'Barium (Ba)',
        br: 'Bromine (Br)',
        ca: 'Calcium (Ca)',
        co: 'Cobalt (Co)',
        cr: 'Chromium (Cr)',
        fe: 'Iron (Fe)',
        k_value: 'Potassium (K)',
        na_value: 'Sodium (Na)',
        rb: 'Rubidium (Rb)',
        sc: 'Scandium (Sc)',
        sm: 'Samarium (Sm)',
        zn: 'Zinc (Zn)'
    };

    samplesArray.forEach(sample => {
        const country = (sample.country || 'Unknown').toString().trim();
        if (!country) return;

        if (!countryStats[country]) {
            countryStats[country] = { count: 0 };
            elementColumns.forEach(element => {
                countryStats[country][element] = 0;
            });
        }

        const bucket = countryStats[country];
        bucket.count += 1;
        elementColumns.forEach(element => {
            bucket[element] += parseFloat(sample[element]) || 0;
        });
    });

    const countryNames = Object.keys(countryStats).sort();
    const palette = ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6', '#14b8a6', '#f97316', '#eab308'];

    const getAverage = (element, country) => {
        const bucket = countryStats[country];
        return bucket && bucket.count ? bucket[element] / bucket.count : 0;
    };

    const chartLabels = elementColumns.map(element => elementLabels[element] || element);

    const datasets = countryNames.map((country, index) => ({
        label: country,
        data: elementColumns.map(element => getAverage(element, country)),
        backgroundColor: palette[index % palette.length],
        borderColor: palette[index % palette.length],
        borderWidth: 1,
        borderRadius: 4,
        barPercentage: 0.75,
        categoryPercentage: 0.85
    }));

    renderCombinedComparisonChart('combinedComparisonChart', chartLabels, datasets);
}

/**
 * Helper to configure and draw a combined grouped bar chart for all comparison elements
 */
function renderCombinedComparisonChart(canvasId, labels, datasets) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#e5e7eb',
                        font: { family: 'Outfit', size: 12 },
                        boxWidth: 16,
                        boxHeight: 12,
                        padding: 16
                    }
                },
                tooltip: {
                    backgroundColor: '#1a1d29',
                    titleFont: { family: 'Outfit', weight: 'bold' },
                    bodyFont: { family: 'Inter' },
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.formattedValue} mg/kg`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.08)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#9ca3af',
                        font: { family: 'Inter', size: 11 }
                    },
                    title: {
                        display: true,
                        text: 'Average concentration (mg/kg)',
                        color: '#e5e7eb',
                        font: { family: 'Outfit', size: 12, weight: '600' }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#9ca3af',
                        font: { family: 'Outfit', size: 12, weight: '600' },
                        maxRotation: 45,
                        minRotation: 30,
                        autoSkip: false
                    },
                    title: {
                        display: true,
                        text: 'Element',
                        color: '#e5e7eb',
                        font: { family: 'Outfit', size: 12, weight: '600' }
                    }
                }
            }
        }
    });
}

let elementComparisonChartInstance = null;

function initElementComparison(countryStatsData) {
    const elementSelector = document.getElementById('elementSelector');
    if (!elementSelector) return;

    const nutrients = ['fe', 'zn', 'ca', 'k_value'];
    const contaminants = ['cr', 'ba', 'br', 'co', 'na_value', 'rb', 'sc', 'sm'];

    const elementLabels = {
        ba: 'Barium (Ba)',
        br: 'Bromine (Br)',
        ca: 'Calcium (Ca)',
        co: 'Cobalt (Co)',
        cr: 'Chromium (Cr)',
        fe: 'Iron (Fe)',
        k_value: 'Potassium (K)',
        na_value: 'Sodium (Na)',
        rb: 'Rubidium (Rb)',
        sc: 'Scandium (Sc)',
        sm: 'Samarium (Sm)',
        zn: 'Zinc (Zn)'
    };

    const elementUnits = {
        ba: 'mg/kg',
        br: 'mg/kg',
        ca: 'wt%',
        co: 'mg/kg',
        cr: 'mg/kg',
        fe: 'mg/kg',
        k_value: 'wt%',
        na_value: 'mg/kg',
        rb: 'mg/kg',
        sc: 'mg/kg',
        sm: 'mg/kg',
        zn: 'mg/kg'
    };

    const elementSafeLimits = {
        ba: '0.5 mg/d (WHO/FAO limit)',
        br: '4 mg/kg (WHO/FAO limit)',
        ca: '2500-3000 mg/d (WHO/FAO limit)',
        co: '3.5 mg/kg (WHO/FAO limit)',
        cr: '2.3 mg/kg (WHO/FAO limit)',
        fe: '300 mg/kg (WHO/FAO limit)',
        k_value: '2300-3100 mg/d (WHO/FAO limit)',
        na_value: '1200-1500 mg/d (WHO/FAO limit)',
        rb: '200 mg/kg (WHO/FAO limit)',
        zn: '100 mg/kg (WHO/FAO limit)'
    };

    const updateElementView = () => {
        const selectedEl = elementSelector.value;
        const isNutrient = nutrients.includes(selectedEl);
        const unit = elementUnits[selectedEl] || 'mg/kg';
        const label = elementLabels[selectedEl] || selectedEl;
        
        // 1. Gather countries that actually have data for this element
        const countriesList = Object.keys(countryStatsData).filter(c => {
            if (c.toLowerCase() === 'unknown') return false;
            const stats = countryStatsData[c];
            if (!stats) return false;
            const val = parseFloat(stats['avg_' + selectedEl]);
            if (isNaN(val)) return false;
            
            // Check if it's literature baseline country and has 0 (which means NA)
            if (val === 0 && (c === 'Sri Lanka' || c === 'Bangladesh' || c === 'Iran')) {
                return false;
            }
            return stats.sample_count > 0;
        });

        // Map and sort countries
        const countryData = countriesList.map(c => {
            const stats = countryStatsData[c];
            return {
                country: c,
                value: parseFloat(stats['avg_' + selectedEl]) || 0,
                sampleCount: stats.sample_count
            };
        });

        if (countryData.length === 0) {
            document.getElementById('elementVerdictText').innerHTML = `<p style="margin:0; color: var(--text-secondary);">No database records found measuring <strong>${label}</strong>.</p>`;
            if (elementComparisonChartInstance) {
                elementComparisonChartInstance.destroy();
                elementComparisonChartInstance = null;
            }
            return;
        }

        // Sort: nutrients descending (highest is best), contaminants ascending (lowest is best)
        if (isNutrient) {
            countryData.sort((a, b) => b.value - a.value);
        } else {
            countryData.sort((a, b) => a.value - b.value);
        }

        // 2. Render Text Verdict
        const bestCountry = countryData[0].country;
        const bestVal = countryData[0].value.toFixed(unit === 'wt%' ? 4 : 2);
        
        let verdictHtml = `<p style="margin: 0; color: #fff; font-weight: 600; font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 6px;">`;
        if (isNutrient) {
            verdictHtml += `🏆 For ${label}, <strong>${bestCountry}</strong> is superior with the highest nutritional average of <strong>${bestVal} ${unit}</strong>.`;
        } else {
            verdictHtml += `🏆 For ${label}, <strong>${bestCountry}</strong> is superior (safest) with the lowest contamination average of <strong>${bestVal} ${unit}</strong>.`;
        }
        verdictHtml += `</p>`;

        // Ranking list
        verdictHtml += `<p style="margin: 0 0 10px 0; color: var(--text-secondary); font-size: 0.92rem;">Rankings for ${label} (from best to worst):</p>`;
        verdictHtml += `<ol style="margin: 0; padding-left: 20px; font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">`;
        countryData.forEach((item, index) => {
            const valFormatted = item.value.toFixed(unit === 'wt%' ? 4 : 2);
            verdictHtml += `<li><strong>${item.country}</strong>: ${valFormatted} ${unit} <span style="font-size:0.8rem; color:var(--text-muted);">(${item.sampleCount} samples)</span></li>`;
        });
        verdictHtml += `</ol>`;

        // Safe limit note if exists
        if (elementSafeLimits[selectedEl]) {
            verdictHtml += `<div style="margin-top: 12px; font-size: 0.82rem; color: var(--primary); font-weight: 500;">`;
            verdictHtml += `💡 WHO/FAO Safety Reference Limit: <strong>${elementSafeLimits[selectedEl]}</strong>`;
            verdictHtml += `</div>`;
        }

        document.getElementById('elementVerdictText').innerHTML = verdictHtml;

        // 3. Render Element Comparison Chart
        const chartCanvas = document.getElementById('elementComparisonChart');
        if (!chartCanvas) return;

        if (elementComparisonChartInstance) {
            elementComparisonChartInstance.destroy();
        }

        const chartLabels = countryData.map(item => item.country);
        const chartValues = countryData.map(item => item.value);
        const palette = ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6', '#14b8a6', '#f97316', '#eab308'];

        elementComparisonChartInstance = new Chart(chartCanvas, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: `Average ${label}`,
                    data: chartValues,
                    backgroundColor: chartLabels.map((_, idx) => palette[idx % palette.length]),
                    borderColor: chartLabels.map((_, idx) => palette[idx % palette.length]),
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.4,
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1a1d29',
                        titleFont: { family: 'Outfit', weight: 'bold' },
                        bodyFont: { family: 'Inter' },
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw.toFixed(unit === 'wt%' ? 4 : 2)} ${unit}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.08)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: { family: 'Inter', size: 11 }
                        },
                        title: {
                            display: true,
                            text: `Concentration (${unit})`,
                            color: '#e5e7eb',
                            font: { family: 'Outfit', size: 12, weight: '600' }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: { family: 'Outfit', size: 12, weight: '600' }
                        },
                        title: {
                            display: true,
                            text: 'Country',
                            color: '#e5e7eb',
                            font: { family: 'Outfit', size: 12, weight: '600' }
                        }
                    }
                }
            }
        });
    };

    // Attach event listener
    elementSelector.addEventListener('change', updateElementView);

    // Initial render
    updateElementView();
}

