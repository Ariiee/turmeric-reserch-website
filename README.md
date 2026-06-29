**🌿 Turmeric Forensic Element Analysis Portal**

A web-based research database for profiling the concentration of minor and trace elements in raw and branded turmeric powders, backed by Instrumental Neutron Activation Analysis (INAA) conducted using the Apsara-U nuclear research reactor at BARC, Mumbai.


Based on the research paper: Quantification of minor and trace elements in raw and branded turmeric using INAA utilizing the Apsara-U reactor
Made by Arpita Sharma




📋 Overview

This portal provides an interactive interface to explore elemental composition data (12 elements across 23+ samples) sourced from turmeric specimens collected across India and China. It supports filtering, searching, adding new entries, and visualizing data through interactive charts.

Key Stats

MetricValueSamples Profiled23+Elements Tracked12 (Ba, Br, Ca, Co, Cr, Fe, K, Na, Rb, Sc, Sm, Zn)Analysis MethodINAA (Instrumental Neutron Activation Analysis)Reactor UsedApsara-U, BARC Mumbai


✨ Features


Home Page — Methodology timeline explaining the 6-step INAA workflow
Sample Database — Browse all samples with filter by type (Raw / Branded) and country
WHO/FAO Limit Flags — Elemental values exceeding safe limits are highlighted automatically
Search — Query samples by ID, location, or type
Add / Edit / Delete Samples — Full CRUD operations for managing research data
Graphs & Statistics — Interactive Chart.js visualizations including:

Bar charts comparing elemental concentrations across samples
Average element concentration overview
Statistical highlights (highest Fe, highest Zn, lowest Cr, etc.)






🛠️ Tech Stack

LayerTechnologyBackendPHP (procedural)DatabaseMySQLFrontendHTML5, CSS3, Vanilla JavaScriptChartsChart.jsLocal ServerXAMPP (Apache + MySQL)


🚀 Installation & Setup

Prerequisites


XAMPP (or any Apache + MySQL + PHP stack)
PHP 7.4 or higher
MySQL 5.7 or higher


Steps


Clone the repository


bash   git clone https://github.com/your-username/turmeric-research-portal.git


Move to XAMPP's web root


bash   # On Windows
   mv turmeric-research-portal "C:/xampp/htdocs/turmeric reserch website"

   # On macOS/Linux
   mv turmeric-research-portal /opt/lampp/htdocs/turmeric-portal


Start XAMPP
Open the XAMPP Control Panel and start both Apache and MySQL.
Set up the database

Open your browser and go to http://localhost/phpmyadmin
Click Import and select setup_db.sql from the project folder
This will create the turmeric_db database and populate it with all research data



Open the portal
Navigate to http://localhost/turmeric%20reserch%20website/ in your browser.



🗄️ Database Schema

Database: turmeric_db

Table: turmeric_samples

ColumnTypeDescriptionidINT (PK)Auto-incremented primary keysample_idVARCHAR(20)Unique sample identifier (e.g., RT-1, BT-3, CN-05)sample_typeVARCHAR(20)Raw or BrandedlocationVARCHAR(100)Geographical origin or brand namecountryVARCHAR(50)Country of origin (default: India)baFLOATBarium concentration (µg/g)brFLOATBromine concentration (µg/g)caFLOATCalcium concentration (%)coFLOATCobalt concentration (µg/g)crFLOATChromium concentration (µg/g)feFLOATIron concentration (µg/g)k_valueFLOATPotassium concentration (%)na_valueFLOATSodium concentration (µg/g)rbFLOATRubidium concentration (µg/g)scFLOATScandium concentration (µg/g)smFLOATSamarium concentration (µg/g)znFLOATZinc concentration (µg/g)created_atTIMESTAMPRecord creation time


📁 Project Structure

turmeric reserch website/
│
├── index.php           # Home page with methodology timeline
├── samples.php         # Sample database browser with filters
├── search.php          # Search by sample ID, location, or type
├── graphs.php          # Interactive charts and statistics
├── add_sample.php      # Form to add a new sample
├── edit_sample.php     # Form to edit an existing sample
├── delete_sample.php   # Handler for deleting a sample
│
├── db.php              # MySQL database connection
├── setup_db.sql        # Database schema + seed data (run once)
│
├── header.php          # Shared HTML header and nav
├── footer.php          # Shared HTML footer
│
├── style.css           # Main stylesheet (dark theme)
├── script.js           # Frontend interactivity and chart logic
│
└── images/
    ├── raw_turmeric.png        # Raw turmeric specimen image
    ├── vegetable_turmeric.png  # Vegetable turmeric image
    └── gamma_spectrum.png      # Gamma spectrum reference image


🧪 INAA Methodology (6 Steps)


Sample Collection — Raw, vegetable, and branded turmeric from markets in West Bengal and Delhi NCR
Drying & Preparation — Washing, slicing, air-drying, and oven-drying
Grinding & Foil Wrapping — Powdered into 75–100 mg aliquots, wrapped in high-purity aluminum foil
Neutron Irradiation — 5 hours at ~10¹³ n/cm²/s thermal flux in the Apsara-U reactor (BARC, Mumbai)
Gamma Spectroscopy — After 1-week cooling, measured with HPGe detector + 8K multichannel analyzer
Concentration Calculation — Computed via PHAST software relative to Polish Herbs CRM standards



⚠️ WHO/FAO Limit Flags

The portal automatically highlights values that exceed established safe limits:

ElementLimitIron (Fe)300 µg/gZinc (Zn)100 µg/gChromium (Cr)2.3 µg/gBromine (Br)4.0 µg/g


🤝 Contributing

Contributions, bug reports, and feature requests are welcome! Please open an issue or submit a pull request.


📄 License

© 2026 Arpita Sharma. All rights reserved.
