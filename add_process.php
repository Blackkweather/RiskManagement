<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Process - RiskGuard Pro</title>
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
            max-width: 800px;
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

        .info-box {
            background: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-box i {
            color: #3182ce;
            margin-right: 8px;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding: 10px;
            background: #edf2f7;
            border-radius: 8px;
        }

        .step-number {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .form-row, .form-row-3 {
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
        <a href="processes.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Processes
        </a>

        <div class="header">
            <h1><i class="fas fa-cogs"></i> Add New Process</h1>
            <p>Define a new business process for comprehensive risk management</p>
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Process Management:</strong> Processes represent workflows and procedures within domains. 
            They can be organized hierarchically and linked to specific entities and activities.
        </div>

        <form method="POST" action="api/process.php" id="processForm">
            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Basic Information
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Process Name *</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Enter process name">
                    </div>
                    <div class="form-group">
                        <label for="code">Process Code</label>
                        <input type="text" id="code" name="code" 
                               placeholder="e.g., PROC-001">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Process Description *</label>
                    <textarea id="description" name="description" rows="4" required
                              placeholder="Describe the process purpose, scope, and key objectives..."></textarea>
                </div>
            </div>

            <!-- Process Assignment -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-sitemap"></i>
                    Process Assignment
                </div>
                
                <div class="form-row-3">
                    <div class="form-group">
                        <label for="domaineId">Domain *</label>
                        <select id="domaineId" name="domaineId" required>
                            <option value="">Select domain</option>
                            <option value="1">Security Domain</option>
                            <option value="2">Financial Operations</option>
                            <option value="3">Operations</option>
                            <option value="4">Human Resources</option>
                            <option value="5">Technology</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="entityId">Responsible Entity</label>
                        <select id="entityId" name="entityId">
                            <option value="">Select entity</option>
                            <option value="1">IT Department</option>
                            <option value="2">Development Team</option>
                            <option value="3">Finance Division</option>
                            <option value="4">Operations</option>
                            <option value="5">Quality Assurance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="parentId">Parent Process</label>
                        <select id="parentId" name="parentId">
                            <option value="">No Parent (Root Process)</option>
                            <option value="1">User Authentication</option>
                            <option value="2">Transaction Processing</option>
                            <option value="3">Data Management</option>
                            <option value="4">Quality Control</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Process Details -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-clipboard-list"></i>
                    Process Details
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="processType">Process Type</label>
                        <select id="processType" name="processType">
                            <option value="Core">Core Process</option>
                            <option value="Support">Support Process</option>
                            <option value="Management">Management Process</option>
                            <option value="Operational">Operational Process</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority Level</label>
                        <select id="priority" name="priority">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="owner">Process Owner</label>
                        <input type="text" id="owner" name="owner" 
                               placeholder="Person responsible for the process">
                    </div>
                    <div class="form-group">
                        <label for="frequency">Execution Frequency</label>
                        <select id="frequency" name="frequency">
                            <option value="Continuous">Continuous</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Quarterly">Quarterly</option>
                            <option value="Annually">Annually</option>
                            <option value="Ad-hoc">Ad-hoc</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputs">Process Inputs</label>
                    <textarea id="inputs" name="inputs" rows="3" 
                              placeholder="List the inputs required for this process (data, resources, triggers)..."></textarea>
                </div>

                <div class="form-group">
                    <label for="outputs">Process Outputs</label>
                    <textarea id="outputs" name="outputs" rows="3" 
                              placeholder="List the outputs produced by this process (deliverables, results)..."></textarea>
                </div>

                <div class="form-group">
                    <label for="kpis">Key Performance Indicators (KPIs)</label>
                    <textarea id="kpis" name="kpis" rows="2" 
                              placeholder="Define measurable indicators for process performance..."></textarea>
                </div>
            </div>

            <!-- Process Steps -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-list-ol"></i>
                    Process Steps
                </div>
                
                <div id="processSteps">
                    <div class="step-indicator">
                        <div class="step-number">1</div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <input type="text" name="steps[]" placeholder="Describe the first step of the process...">
                        </div>
                    </div>
                </div>
                
                <button type="button" onclick="addStep()" style="background: #48bb78; color: white; border: none; padding: 8px 16px; border-radius: 6px; margin-top: 10px;">
                    <i class="fas fa-plus"></i> Add Step
                </button>
            </div>

            <!-- Risk Considerations -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Risk Considerations
                </div>
                
                <div class="form-group">
                    <label for="riskFactors">Potential Risk Factors</label>
                    <textarea id="riskFactors" name="riskFactors" rows="3" 
                              placeholder="Identify potential risks associated with this process..."></textarea>
                </div>

                <div class="form-group">
                    <label for="controls">Existing Controls</label>
                    <textarea id="controls" name="controls" rows="3" 
                              placeholder="Describe existing controls and safeguards for this process..."></textarea>
                </div>
            </div>

            <div class="btn-group">
                <a href="processes.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Process
                </button>
            </div>
        </form>
    </div>

    <script>
        let stepCounter = 1;

        function addStep() {
            stepCounter++;
            const stepsContainer = document.getElementById('processSteps');
            const newStep = document.createElement('div');
            newStep.className = 'step-indicator';
            newStep.innerHTML = `
                <div class="step-number">${stepCounter}</div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <input type="text" name="steps[]" placeholder="Describe step ${stepCounter} of the process...">
                </div>
                <button type="button" onclick="removeStep(this)" style="background: #e53e3e; color: white; border: none; padding: 8px 12px; border-radius: 6px; margin-left: 10px;">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            stepsContainer.appendChild(newStep);
        }

        function removeStep(button) {
            button.parentElement.remove();
            // Renumber steps
            const steps = document.querySelectorAll('.step-number');
            steps.forEach((step, index) => {
                step.textContent = index + 1;
            });
            stepCounter = steps.length;
        }

        // Auto-generate process code based on process name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const codeField = document.getElementById('code');
            
            if (name && !codeField.value) {
                const words = name.split(' ');
                let code = '';
                words.forEach(word => {
                    if (word.length > 0) {
                        code += word.charAt(0).toUpperCase();
                    }
                });
                code += String(Math.floor(Math.random() * 900) + 100).padStart(3, '0');
                codeField.value = code;
            }
        });

        // Form validation
        document.getElementById('processForm').addEventListener('submit', function(e) {
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

