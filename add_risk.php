<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Risk - RiskGuard Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 900px;
            position: relative;
            overflow: hidden;
            max-height: 90vh;
            overflow-y: auto;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2d3748;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            color: #718096;
            font-size: 1.1rem;
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .section-title {
            color: #2d3748;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3748;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .impact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .slider-group {
            margin-bottom: 15px;
        }

        .slider-group label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .slider-value {
            background: #667eea;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        input[type="range"] {
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #e2e8f0;
            outline: none;
            -webkit-appearance: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #667eea;
            cursor: pointer;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
            transform: translateY(-2px);
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
            transform: translateX(-3px);
        }

        .criticality-display {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-top: 15px;
        }

        .criticality-value {
            font-size: 2rem;
            font-weight: bold;
            color: #2d3748;
        }

        .criticality-label {
            color: #718096;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-row, .form-row-3, .impact-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="risks.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Risks
        </a>

        <div class="header">
            <h1><i class="fas fa-exclamation-triangle"></i> Add New Risk</h1>
            <p>Register and assess a new risk for comprehensive management</p>
        </div>

        <form method="POST" action="api/risk.php" id="riskForm">
            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Basic Information
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Risk Name *</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Enter risk name">
                    </div>
                    <div class="form-group">
                        <label for="code">Risk Code</label>
                        <input type="text" id="code" name="code" 
                               placeholder="e.g., RISK-001">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Risk Description</label>
                    <textarea id="description" name="description" rows="3" 
                              placeholder="Describe the risk in detail..."></textarea>
                </div>

                <div class="form-group">
                    <label for="cause">Risk Cause *</label>
                    <textarea id="cause" name="cause" rows="2" required
                              placeholder="What causes this risk to occur?"></textarea>
                </div>
            </div>

            <!-- Project Assignment -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-project-diagram"></i>
                    Project Assignment
                </div>
                
                <div class="form-row-3">
                    <div class="form-group">
                        <label for="projectId">Project *</label>
                        <select id="projectId" name="projectId" required>
                            <option value="">Select project</option>
                            <option value="1">Customer Portal Redesign</option>
                            <option value="2">Mobile Banking App</option>
                            <option value="3">ERP System Implementation</option>
                            <option value="4">E-commerce Platform</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="entityId">Entity *</label>
                        <select id="entityId" name="entityId" required>
                            <option value="">Select entity</option>
                            <option value="1">IT Department</option>
                            <option value="2">Development Team</option>
                            <option value="3">Finance Division</option>
                            <option value="4">Operations</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activityId">Activity *</label>
                        <select id="activityId" name="activityId" required>
                            <option value="">Select activity</option>
                            <option value="1">User Authentication</option>
                            <option value="2">Data Processing</option>
                            <option value="3">System Integration</option>
                            <option value="4">Quality Assurance</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Risk Assessment -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Risk Assessment
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <div class="slider-group">
                            <label>
                                Frequency (Likelihood)
                                <span class="slider-value" id="frequencyValue">3</span>
                            </label>
                            <input type="range" id="frequency" name="frequency" 
                                   min="1" max="5" value="3" 
                                   oninput="updateSliderValue('frequency', 'frequencyValue')">
                            <small>1 = Very Rare, 5 = Very Frequent</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="riskFamilyId">Risk Family</label>
                        <select id="riskFamilyId" name="riskFamilyId">
                            <option value="">Select risk family</option>
                            <option value="1">Operational Risk</option>
                            <option value="2">Financial Risk</option>
                            <option value="3">Strategic Risk</option>
                            <option value="4">Compliance Risk</option>
                            <option value="5">Technology Risk</option>
                        </select>
                    </div>
                </div>

                <!-- Impact Assessment -->
                <div class="impact-grid">
                    <div class="slider-group">
                        <label>
                            Financial Impact
                            <span class="slider-value" id="financialImpactValue">3</span>
                        </label>
                        <input type="range" id="financialImpact" name="financialImpact" 
                               min="1" max="5" value="3" 
                               oninput="updateSliderValue('financialImpact', 'financialImpactValue'); calculateCriticality()">
                    </div>
                    
                    <div class="slider-group">
                        <label>
                            Legal Impact
                            <span class="slider-value" id="legalImpactValue">3</span>
                        </label>
                        <input type="range" id="legalImpact" name="legalImpact" 
                               min="1" max="5" value="3" 
                               oninput="updateSliderValue('legalImpact', 'legalImpactValue'); calculateCriticality()">
                    </div>
                    
                    <div class="slider-group">
                        <label>
                            Reputation Impact
                            <span class="slider-value" id="reputationImpactValue">3</span>
                        </label>
                        <input type="range" id="reputationImpact" name="reputationImpact" 
                               min="1" max="5" value="3" 
                               oninput="updateSliderValue('reputationImpact', 'reputationImpactValue'); calculateCriticality()">
                    </div>
                    
                    <div class="slider-group">
                        <label>
                            Activity Impact
                            <span class="slider-value" id="activityImpactValue">3</span>
                        </label>
                        <input type="range" id="activityImpact" name="activityImpact" 
                               min="1" max="5" value="3" 
                               oninput="updateSliderValue('activityImpact', 'activityImpactValue'); calculateCriticality()">
                    </div>
                    
                    <div class="slider-group">
                        <label>
                            People Impact
                            <span class="slider-value" id="peopleImpactValue">3</span>
                        </label>
                        <input type="range" id="peopleImpact" name="peopleImpact" 
                               min="1" max="5" value="3" 
                               oninput="updateSliderValue('peopleImpact', 'peopleImpactValue'); calculateCriticality()">
                    </div>
                </div>

                <div class="criticality-display">
                    <div class="criticality-value" id="criticalityDisplay">15</div>
                    <div class="criticality-label">Calculated Risk Criticality</div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-clipboard-list"></i>
                    Additional Details
                </div>
                
                <div class="form-group">
                    <label for="details">Risk Details</label>
                    <textarea id="details" name="details" rows="3" 
                              placeholder="Additional context, background information, or specific scenarios..."></textarea>
                </div>

                <div class="form-group">
                    <label for="existantDb">Existing Controls/Measures</label>
                    <textarea id="existantDb" name="existantDb" rows="2" 
                              placeholder="Describe any existing controls or mitigation measures..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="evaluation">Risk Evaluation (1-5)</label>
                        <select id="evaluation" name="evaluation">
                            <option value="1">1 - Very Low</option>
                            <option value="2">2 - Low</option>
                            <option value="3" selected>3 - Medium</option>
                            <option value="4">4 - High</option>
                            <option value="5">5 - Very High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="active">Risk Status</label>
                        <select id="active" name="active">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <a href="risks.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Risk
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateSliderValue(sliderId, valueId) {
            const slider = document.getElementById(sliderId);
            const valueDisplay = document.getElementById(valueId);
            valueDisplay.textContent = slider.value;
        }

        function calculateCriticality() {
            const frequency = parseInt(document.getElementById('frequency').value);
            const financial = parseInt(document.getElementById('financialImpact').value);
            const legal = parseInt(document.getElementById('legalImpact').value);
            const reputation = parseInt(document.getElementById('reputationImpact').value);
            const activity = parseInt(document.getElementById('activityImpact').value);
            const people = parseInt(document.getElementById('peopleImpact').value);
            
            // Calculate average impact
            const avgImpact = (financial + legal + reputation + activity + people) / 5;
            
            // Calculate criticality (frequency × average impact)
            const criticality = Math.round(frequency * avgImpact);
            
            document.getElementById('criticalityDisplay').textContent = criticality;
            
            // Update color based on criticality level
            const display = document.getElementById('criticalityDisplay');
            if (criticality >= 20) {
                display.style.color = '#e53e3e';
            } else if (criticality >= 15) {
                display.style.color = '#dd6b20';
            } else if (criticality >= 10) {
                display.style.color = '#d69e2e';
            } else {
                display.style.color = '#38a169';
            }
        }

        // Auto-generate risk code
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const codeField = document.getElementById('code');
            
            if (name && !codeField.value) {
                const words = name.split(' ');
                let code = 'RISK-';
                words.forEach(word => {
                    if (word.length > 0) {
                        code += word.charAt(0).toUpperCase();
                    }
                });
                code += String(Math.floor(Math.random() * 900) + 100).padStart(3, '0');
                codeField.value = code;
            }
        });

        // Initialize criticality calculation
        calculateCriticality();

        // Form validation
        document.getElementById('riskForm').addEventListener('submit', function(e) {
            const required = this.querySelectorAll('[required]');
            let valid = true;
            
            required.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#e53e3e';
                    valid = false;
                } else {
                    field.style.borderColor = '#e2e8f0';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
</body>
</html>

