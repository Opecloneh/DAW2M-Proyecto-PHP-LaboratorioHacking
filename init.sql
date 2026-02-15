-- INSERTAR MÓDULOS
INSERT INTO modulos (id, name, description) VALUES
                                                (1, 'SQL Injection', 'Vulnerabilidades relacionadas con inyecciones SQL.'),
                                                (2, 'XSS', 'Cross-Site Scripting y ejecución de JavaScript en el navegador.'),
                                                (3, 'IDOR', 'Insecure Direct Object Reference y fallos de autorización.'),
                                                (4, 'File Upload', 'Vulnerabilidades en sistemas de subida de archivos.');

-- INSERTAR LABORATORIOS
INSERT INTO laboratorios (title, description, difficulty, module_id, solution) VALUES
                                                                                   ('SQLi Login – Login Bypass',
                                                                                    'Realiza un login bypass explotando una vulnerabilidad SQL Injection en el formulario de autenticación con un Boolean Based.',
                                                                                    'Fácil', 1, ''' OR 1=1 -- '),

                                                                                   ('XSS Reflejado',
                                                                                    'Explotar un XSS reflejado en un parámetro vulnerable de búsqueda, con una alerta donde pongas "1".',
                                                                                    'Media', 2, '<script>alert("1")</script>'),

                                                                                   ('IDOR Básico',
                                                                                    'Accede al usuario con id 4 teniendo en cuenta que tu URL es /user/2.',
                                                                                    'Fácil', 3, '/user/4'),

                                                                                   ('File Upload Inseguro',
                                                                                    'Subir una webshell shell.php teniendo en cuenta que el servidor solo acepta .jpg.',
                                                                                    'Media', 4, 'shell.php.jpg');
