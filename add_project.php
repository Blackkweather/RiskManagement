<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Project - RiskGuard Pro</title>
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
            max-width: 700px;
            position: relative;
            overflow: hidden;
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

        .form-group {
            margin-bottom: 25px;
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
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
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
        <a href="projects.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Projects
        </a>

        <div class="header">
            <h1><i class="fas fa-project-diagram"></i> Add New Project</h1>
            <p>Create a new project for risk management tracking</p>
        </div>

        <form method="POST" action="api/project.php" id="projectForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-folder"></i> Project Name *
                    </label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Enter project name">
                </div>

                <div class="form-group">
                    <label for="code">
                        <i class="fas fa-tag"></i> Project Code
                    </label>
                    <input type="text" id="code" name="code" 
                           placeholder="e.g., PRJ001">
                </div>
            </div>

            <div class="form-group">
                <label for="clientId">
                    <i class="fas fa-building"></i> Client Organization *
                </label>
                <select id="clientId" name="clientId" required>
                    <option value="">Select client organization</option>
                    <option value="1">TechCorp Solutions</option>
                    <option value="2">Global Finance Inc</option>
                    <option value="3">Healthcare Partners</option>
                    <option value="4">Manufacturing Pro</option>
                    <option value="5">Retail Excellence</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">
                    <i class="fas fa-align-left"></i> Project Description
                </label>
                <textarea id="description" name="description" rows="4" 
                          placeholder="Describe the project objectives, scope, and key deliverables..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="startDate">
                        <i class="fas fa-calendar-alt"></i> Start Date
                    </label>
                    <input type="date" id="startDate" name="startDate">
                </div>

                <div class="form-group">
                    <label for="endDate">
                        <i class="fas fa-calendar-check"></i> Target End Date
                    </label>
                    <input type="date" id="endDate" name="endDate">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="budget">
                        <i class="fas fa-dollar-sign"></i> Project Budget
                    </label>
                    <input type="number" id="budget" name="budget" step="0.01" 
                           placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="priority">
                        <i class="fas fa-exclamation-triangle"></i> Priority Level
                    </label>
                    <select id="priority" name="priority">
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="active" name="active" value="1" checked>
                    <label for="active">
                        <i class="fas fa-play-circle"></i> Project is Active
                    </label>
                </div>
                <small style="color: #718096; margin-left: 25px;">
                    Active projects will appear in dashboards and reports
                </small>
            </div>

            <div class="btn-group">
                <a href="projects.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Project
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto-generate project code based on project name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const codeField = document.getElementById('code');
            
            if (name && !codeField.value) {
                // Generate code from first letters of words + random number
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

        // Set default start date to today
        document.getElementById('startDate').value = new Date().toISOString().split('T')[0];

        // Form validation
        document.getElementById('projectForm').addEventListener('submit', function(e) {
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
            
            // Validate end date is after start date
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            
            if (endDate && startDate && endDate <= startDate) {
                document.getElementById('endDate').style.borderColor = '#e53e3e';
                alert('End date must be after start date.');
                valid = false;
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            }
        });
    </script>
</body>
</html>

