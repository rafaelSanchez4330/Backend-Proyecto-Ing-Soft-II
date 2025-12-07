@extends('layouts.dashboard')

@section('content')
<div class="main-content" style="padding: 2rem; color: #e1e1e1;">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Back Button -->
        <div style="margin-bottom: 1rem;">
            <a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background-color: #1f2937; color: #fff; text-decoration: none; border-radius: 0.5rem; border: 1px solid #374151; transition: background-color 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Volver al Dashboard
            </a>
        </div>

        <!-- Header -->
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; font-weight: bold; margin-bottom: 1rem; color: #3b82f6;">UDINT</h1>
            <p style="font-size: 1.2rem; color: #9ca3af; max-width: 800px; margin: 0 auto;">
                Plataforma de Inteligencia de la Unidad de Delitos Informáticos de la Universidad Politécnica de San Luis Potosí
            </p>
        </div>

        <!-- Objective -->
        <div style="background-color: #1f2937; padding: 2rem; border-radius: 0.5rem; margin-bottom: 3rem; border: 1px solid #374151;">
            <h2 style="font-size: 1.5rem; color: #fff; margin-bottom: 1rem; border-bottom: 2px solid #3b82f6; padding-bottom: 0.5rem; display: inline-block;">Objetivo</h2>
            <p style="margin-bottom: 1rem; line-height: 1.6;">
                Desarrollar una plataforma integral de inteligencia que permita llevar un control y seguimiento de casos de investigación OSINT, así como tener una referencia en términos de herramientas y material para el trabajo con OSINT.
            </p>
            <p style="line-height: 1.6;">
                <strong>Objetivo Académico:</strong> Poder integrar de manera práctica algunos de los temas vistos durante el semestre, específicamente el tema de gestión de proyectos y la administración de la configuración y control de cambios, complementando con algunos temas del semestre anterior tal como, ingeniería de requerimientos, pruebas y métodos ágiles.
            </p>
        </div>

        <!-- Modules Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">

            <!-- Modulo Herramientas -->
            <div style="background-color: #1f2937; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #374151;">
                <h3 style="color: #60a5fa; margin-bottom: 0.5rem; font-weight: bold;">Módulo Herramientas y Referencias</h3>
                <p style="color: #9ca3af; margin-bottom: 1rem; font-size: 0.9rem; font-style: italic;">Creadores del Bot ChatGPT</p>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">América Fabiola Guerra Ramírez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">179884@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Hazel Mario Avalos Rangel</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">181801@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Angel Josue Gonzalez Delgado</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182837@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Erick Ismael Ojeda Sanchez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182529@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Eric Leonardo Velazquez Hernandez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">179798@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Diego Lopez Castro</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182032@upslp.edu.mx</div>
                    </li>
                </ul>
            </div>

            <!-- Modulo Administrador -->
            <div style="background-color: #1f2937; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #374151;">
                <h3 style="color: #60a5fa; margin-bottom: 1rem; font-weight: bold;">Módulo Administrador</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Bryan Yareth Gámez Contreras</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182934@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Mildred Guadalupe Sánchez García</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182245@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Jazmín Vanesa Rojas Flores</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182463@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Bryan Ramírez García</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">181679@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Gilberto Emiliano Turrubiartes Rodriguez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182178@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Coral Jazmín Domínguez García</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">179761@upslp.edu.mx</div>
                    </li>
                </ul>
            </div>

             <!-- Modulo Capturista -->
            <div style="background-color: #1f2937; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #374151;">
                <h3 style="color: #60a5fa; margin-bottom: 0.5rem; font-weight: bold;">Módulo Capturista</h3>
                <p style="color: #9ca3af; margin-bottom: 1rem; font-size: 0.9rem; font-style: italic;">Creadores del Bot WhatsApp</p>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Daniel Alejandro Venegas Rivera</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">177603@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Daniel Gil González</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">178396@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">José Rodrigo Bravo Llanas</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">178678@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Alejandro Valente Rosas Vazquez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">179686@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Rodolfo Gutiérrez Hernández</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">179598@upslp.edu.mx</div>
                    </li>
                     <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Diego Ortiz Cerda</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">178575@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Grimaldo Castillo</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">177270@upslp.edu.mx</div>
                    </li>
                </ul>
            </div>

            <!-- Modulo Consultor -->
            <div style="background-color: #1f2937; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #374151;">
                <h3 style="color: #60a5fa; margin-bottom: 0.5rem; font-weight: bold;">Módulo Consultor</h3>
                <p style="color: #9ca3af; margin-bottom: 1rem; font-size: 0.9rem; font-style: italic;">Creadores del Bot Telegram</p>
                 <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Rafael Sánchez Saucedo</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182712@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Yazmin Guerrero Guevara</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182483@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Juan Pablo González Narvaez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">179804@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Miguel Angel Loredo Martinez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">178424@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Diego Osvaldo Hernández Fernández</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182217@upslp.edu.mx</div>
                    </li>
                     <li style="margin-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Yael Quintanilla Ramirez</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">181914@upslp.edu.mx</div>
                    </li>
                </ul>
            </div>

            <!-- Modulo Reportes -->
            <div style="background-color: #1f2937; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #374151;">
                <h3 style="color: #60a5fa; margin-bottom: 1rem; font-weight: bold;">Módulo Reportes</h3>
                 <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">José Gilberto Fajardo Zapata</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182006@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Sebastian Martinez Monreal</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">174117@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">César Raúl Rodríguez Cobián</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">182028@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Fernando Otero Muñiz</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">183246@upslp.edu.mx</div>
                    </li>
                    <li style="margin-bottom: 0.5rem; border-bottom: 1px solid #374151; padding-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Puente Niño Sandra Elizabeth</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">177886@upslp.edu.mx</div>
                    </li>
                     <li style="margin-bottom: 0.5rem;">
                        <div style="font-weight: 500;">Karina Mendoza Aguado</div>
                        <div style="color: #9ca3af; font-size: 0.9rem;">179859@upslp.edu.mx</div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
