<?php
$page_title = "Home";
include 'header.php';
?>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-text">
        <h1>Turmeric Element <span>Analysis Portal</span></h1>
        <p>
            An advanced analytical database profiling the concentration of minor and trace elements in raw and branded turmeric powders. Backed by Instrumental Neutron Activation Analysis (INAA) utilizing the state-of-the-art Apsara-U nuclear research reactor.
        </p>
        
        <!-- Key Stats Panel -->
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number">23</div>
                <div class="stat-label">Samples Profiled</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">12</div>
                <div class="stat-label">Elements Stored</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">INAA</div>
                <div class="stat-label">Analysis Method</div>
            </div>
        </div>
        
        <div style="margin-top: 35px;" class="btn-group">
            <a href="samples.php" class="btn btn-primary">Browse Sample Database</a>
            <a href="graphs.php" class="btn btn-secondary">View Visual Charts</a>
        </div>
    </div>
    
    <div class="hero-image-wrapper">
        <img src="images/raw_turmeric.png" alt="Raw Dried Turmeric Specimen" class="hero-image">
    </div>
</div>

<!-- Methodology Timeline -->
<section>
    <div class="section-header">
        <div class="section-title">
            <h2>Analytical Methodology</h2>
            <p>Step-by-step INAA experimental workflow utilized in the research paper</p>
        </div>
    </div>
    
    <div class="timeline">
        <div class="timeline-item">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="timeline-step">Step 1</div>
                <h4 class="timeline-title">Sample Collection</h4>
                <p class="timeline-desc">Raw dried whole turmeric, vegetable turmeric, and branded packaged samples were procured from markets in West Bengal and Delhi NCR regions (Noida, Ghaziabad, etc.).</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="timeline-step">Step 2</div>
                <h4 class="timeline-title">Drying & Preparation</h4>
                <p class="timeline-desc">Samples were washed thoroughly to remove soil residue, sliced (for fresh samples), air-dried, and oven-dried to prevent microbial degradation.</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="timeline-step">Step 3</div>
                <h4 class="timeline-title">Grinding & Foil Wrapping</h4>
                <p class="timeline-desc">Dried roots were ground into fine powders using an agate mortar. Aliquots of 75–100 mg of powder were wrapped in high-purity aluminum foil packets alongside reference materials.</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="timeline-step">Step 4</div>
                <h4 class="timeline-title">Neutron Irradiation</h4>
                <p class="timeline-desc">Samples were loaded in a tray rod and irradiated for 5 hours at a thermal neutron flux of ~10<sup>13</sup> n/cm²/s in the upgraded 2 MWt Apsara-U research reactor (BARC, Mumbai).</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="timeline-step">Step 5</div>
                <h4 class="timeline-title">Gamma Spec Detection</h4>
                <p class="timeline-desc">Following a cooling period of 1 week, gamma-ray activities of isotopes were measured using a high-efficiency High-Purity Germanium (HPGe) detector coupled to an 8K multichannel analyzer.</p>
            </div>
        </div>
        
        <div class="timeline-item">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
                <div class="timeline-step">Step 6</div>
                <h4 class="timeline-title">Elemental Concentration Calculation</h4>
                <p class="timeline-desc">Peak heights and peak areas were calculated using the Peak Height Analysis (PHAST) software. Concentrations were computed relative to the Mixed Polish Herbs CRM standards.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
