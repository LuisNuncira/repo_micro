<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Retrospectivas Ágiles</title>
    </head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Registro de Retrospectivas Ágiles</h1>
            <p>Facilita el seguimiento y documentación de reuniones de retrospectiva en Scrum</p>
        </div>

        <div class="main-content">
            <div class="sidebar">
                <div class="nav-buttons">
                    <button class="btn btn-primary" onclick="showView('home')">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        Inicio
                    </button>
                    <button class="btn btn-secondary" onclick="showView('new')">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        Nueva
                    </button>
                    <button class="btn btn-success" onclick="showView('history')">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06L6.64 16.36C8.27 17.99 10.51 19 13 19c4.97 0 9-4.03 9-9s-4.03-9-9-9z"/></svg>
                        Historial
                    </button>
                </div>

                <div id="quick-stats">
                    <h3>📊 Estadísticas Rápidas</h3>
                    <div style="margin-top: 15px;">
                        <p><strong>Total Retrospectivas:</strong> <span id="total-count">0</span></p>
                        <p><strong>Último Sprint:</strong> <span id="last-sprint">-</span></p>
                        <p><strong>Acciones Pendientes:</strong> <span id="pending-actions">0</span></p>
                    </div>
                </div>
            </div>

            <div class="content-panel">
                <!-- Vista de Inicio -->
                <div id="home-view" class="view">
                    <h2>¡Bienvenido al Registro de Retrospectivas Ágiles!</h2>
                    
                    <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <h3>🎯 ¿Qué es una retrospectiva en Scrum?</h3>
                        <p>La retrospectiva es una reunión al final de cada sprint donde el equipo reflexiona sobre su trabajo, identifica fortalezas y debilidades, y acuerda acciones de mejora. Es clave para fomentar la mejora continua.</p>
                    </div>

                    <div style="background: #f3e5f5; padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <h3>✨ Características principales:</h3>
                        <ul style="margin-left: 20px; margin-top: 10px;">
                            <li>📝 Registro organizado por sprint</li>
                            <li>✅ Seguimiento de aspectos positivos</li>
                            <li>❌ Identificación de puntos de mejora</li>
                            <li>🎯 Gestión de acciones y compromisos</li>
                            <li>📈 Historial completo de retrospectivas</li>
                            <li>🔄 Seguimiento del cumplimiento de acciones</li>
                        </ul>
                    </div>

                    <div style="text-align: center; margin-top: 30px;">
                        <button class="btn btn-primary" onclick="showView('new')" style="font-size: 18px; padding: 15px 30px;">
                            🚀 Crear Primera Retrospectiva
                        </button>
                    </div>
                </div>

                <!-- Vista Nueva Retrospectiva -->
                <div id="new-view" class="view hidden">
                    <h2>📝 Nueva Retrospectiva</h2>
                    
                    <form id="retrospective-form">
                        <div class="form-group">
                            <label for="sprint-number">Número de Sprint:</label>
                            <input type="number" id="sprint-number" required min="1">
                        </div>

                        <div class="form-group">
                            <label for="sprint-dates">Fechas del Sprint:</label>
                            <input type="text" id="sprint-dates" placeholder="Ej: 01/12/2024 - 15/12/2024">
                        </div>

                        <div class="form-group">
                            <label for="team-members">Miembros del Equipo:</label>
                            <input type="text" id="team-members" placeholder="Separados por comas">
                        </div>

                        <div id="previous-actions-section" class="hidden">
                            <h3>🔄 Revisión de Acciones Anteriores</h3>
                            <div id="previous-actions-list"></div>
                        </div>

                        <h3>✅ ¿Qué funcionó bien?</h3>
                        <div class="form-group">
                            <textarea id="positives" placeholder="Lista las cosas positivas del sprint..."></textarea>
                        </div>

                        <h3>❌ ¿Qué necesita mejorar?</h3>
                        <div class="form-group">
                            <textarea id="negatives" placeholder="Lista los aspectos que necesitan mejora..."></textarea>
                        </div>

                        <h3>🎯 Acciones y Compromisos</h3>
                        <div class="form-group">
                            <textarea id="actions" placeholder="Lista las acciones acordadas para el próximo sprint..."></textarea>
                        </div>

                        <div style="text-align: center; margin-top: 30px;">
                            <button type="submit" class="btn btn-success" style="font-size: 18px; padding: 15px 30px;">
                                💾 Guardar Retrospectiva
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Vista Historial -->
                <div id="history-view" class="view hidden">
                    <h2>📚 Historial de Retrospectivas</h2>
                    <div id="retrospectives-list">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                            <h3>No hay retrospectivas registradas</h3>
                            <p>Comienza creando tu primera retrospectiva</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
