<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Control - RiskGuard Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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

        @media (max-width: 768px) {
            .form-row {
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
        <a href="controls.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Controls
        </a>

        <div class="header">
            <h1><i class="fas fa-shield-alt"></i> Add New Control</h1>
            <p>Register a new control to manage risks effectively</p>
        </div>

        <form method="POST" action="api/risk-control.php" id="controlForm">
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Control Information
                </div>

                <div class="form-group">
                    <label for="name">Control Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Enter control name" />
                </div>

                <div class="form-group">
                    <label for="proposedControl">Proposed Control *</label>
                    <input type="text" id="proposedControl" name="proposedControl" required placeholder="Describe the proposed control" />
                </div>

                <div class="form-group">
                    <label for="proposedControlDescription">Control Description</label>
                    <textarea id="proposedControlDescription" name="proposedControlDescription" rows="3" placeholder="Detailed description of the control"></textarea>
                </div>

                <div class="form-group">
                    <label for="riskId">Associated Risk *</label>
                    <select id="riskId" name="riskId" required>
                        <option value="">Select associated risk</option>
                        <option value="1">Risk 1</option>
                        <option value="2">Risk 2</option>
                        <option value="3">Risk 3</option>
                        <option value="4">Risk 4</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="meanIndicator">Mean Indicator</label>
                        <input type="number" id="meanIndicator" name="meanIndicator" min="0" max="10" step="0.1" placeholder="0.0" />
                    </div>
                    <div class="form-group">
                        <label for="meanIntegrated">Mean Integrated</label>
                        <input type="number" id="meanIntegrated" name="meanIntegrated" min="0" max="10" step="0.1" placeholder="0.0" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="meanManualPost">Mean Manual Post</label>
                        <input type="number" id="meanManualPost" name="meanManualPost" min="0" max="10" step="0.1" placeholder="0.0" />
                    </div>
                    <div class="form-group">
                        <label for="meanManualPre">Mean Manual Pre</label>
                        <input type="number" id="meanManualPre" name="meanManualPre" min="0" max="10" step="0.1" placeholder="0.0" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="meanOrganization">Mean Organization</label>
                        <input type="number" id="meanOrganization" name="meanOrganization" min="0" max="10" step="0.1" placeholder="0.0" />
                    </div>
                    <div class="form-group">
                        <label for="meanReference">Mean Reference</label>
                        <input type="number" id="meanReference" name="meanReference" min="0" max="10" step="0.1" placeholder="0.0" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="meanProgrammed">Mean Programmed</label>
                        <input type="number" id="meanProgrammed" name="meanProgrammed" min="0" max="10" step="0.1" placeholder="0.0" />
                    </div>
                    <div class="form-group">
                        <label for="evaluation">Evaluation</label>
                        <select id="evaluation" name="evaluation">
                            <option value="1">1 - Very Low</option>
                            <option value="2">2 - Low</option>
                            <option value="3" selected>3 - Medium</option>
                            <option value="4">4 - High</option>
                            <option value="5">5 - Very High</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <a href="controls.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Control
                </button>
            </div>
        </form>
    </div>
</body>
</html>
