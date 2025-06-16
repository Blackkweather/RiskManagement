<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Entity - RiskGuard Pro</title>
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
        <a href="entities.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Entities
        </a>

        <div class="header">
            <h1><i class="fas fa-sitemap"></i> Add New Entity</h1>
            <p>Create a new organizational entity for project management</p>
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Entity Hierarchy:</strong> Entities represent organizational units within projects. 
            You can create parent-child relationships to model your organizational structure.
        </div>

        <form method="POST" action="api/entity.php" id="entityForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-building"></i> Entity Name *
                    </label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Enter entity name">
                </div>

                <div class="form-group">
                    <label for="code">
                        <i class="fas fa-tag"></i> Entity Code
                    </label>
                    <input type="text" id="code" name="code" 
                           placeholder="e.g., IT001">
                </div>
            </div>

            <div class="form-group">
                <label for="projectId">
                    <i class="fas fa-project-diagram"></i> Project *
                </label>
                <select id="projectId" name="projectId" required>
                    <option value="">Select project</option>
                    <option value="1">Customer Portal Redesign</option>
                    <option value="2">Mobile Banking App</option>
                    <option value="3">ERP System Implementation</option>
                    <option value="4">Data Migration Project</option>
                    <option value="5">E-commerce Platform</option>
                    <option value="6">Security Audit System</option>
                </select>
            </div>

            <div class="form-group">
                <label for="parentId">
                    <i class="fas fa-layer-group"></i> Parent Entity
                </label>
                <select id="parentId" name="parentId">
                    <option value="">No Parent (Root Entity)</option>
                    <option value="1">IT Department</option>
                    <option value="2">Finance Division</option>
                    <option value="3">Operations</option>
                    <option value="4">Human Resources</option>
                    <option value="5">Marketing</option>
                </select>
                <small style="color: #718096; margin-top: 5px; display: block;">
                    Select a parent entity to create a hierarchical structure
                </small>
            </div>

            <div class="form-group">
                <label for="description">
                    <i class="fas fa-align-left"></i> Description
                </label>
                <textarea id="description" name="description" rows="4" 
                          placeholder="Describe the entity's role, responsibilities, and scope within the project..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="manager">
                        <i class="fas fa-user-tie"></i> Entity Manager
                    </label>
                    <input type="text" id="manager" name="manager" 
                           placeholder="Manager or responsible person">
                </div>

                <div class="form-group">
                    <label for="location">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </label>
                    <input type="text" id="location" name="location" 
                           placeholder="Physical or virtual location">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="teamSize">
                        <i class="fas fa-users"></i> Team Size
                    </label>
                    <input type="number" id="teamSize" name="teamSize" min="1" 
                           placeholder="Number of team members">
                </div>

                <div class="form-group">
                    <label for="budget">
                        <i class="fas fa-dollar-sign"></i> Budget Allocation
                    </label>
                    <input type="number" id="budget" name="budget" step="0.01" 
                           placeholder="0.00">
                </div>
            </div>

            <div class="form-group">
                <label for="responsibilities">
                    <i class="fas fa-tasks"></i> Key Responsibilities
                </label>
                <textarea id="responsibilities" name="responsibilities" rows="3" 
                          placeholder="List the main responsibilities and deliverables of this entity..."></textarea>
            </div>

            <div class="btn-group">
                <a href="entities.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Entity
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto-generate entity code based on entity name
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

        // Update parent entity options based on selected project
        document.getElementById('projectId').addEventListener('change', function() {
            const projectId = this.value;
            const parentSelect = document.getElementById('parentId');
            
            // Clear existing options except the first one
            parentSelect.innerHTML = '<option value="">No Parent (Root Entity)</option>';
            
            if (projectId) {
                // Add project-specific entities (this would normally come from an API)
                const entities = {
                    '1': [
                        {id: 1, name: 'IT Department'},
                        {id: 2, name: 'Development Team'},
                        {id: 3, name: 'QA Team'}
                    ],
                    '2': [
                        {id: 4, name: 'Finance Division'},
                        {id: 5, name: 'Risk Management'},
                        {id: 6, name: 'Compliance'}
                    ],
                    '3': [
                        {id: 7, name: 'Operations'},
                        {id: 8, name: 'IT Infrastructure'},
                        {id: 9, name: 'Change Management'}
                    ]
                };
                
                const projectEntities = entities[projectId] || [];
                projectEntities.forEach(entity => {
                    const option = document.createElement('option');
                    option.value = entity.id;
                    option.textContent = entity.name;
                    parentSelect.appendChild(option);
                });
            }
        });

        // Form validation
        document.getElementById('entityForm').addEventListener('submit', function(e) {
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

