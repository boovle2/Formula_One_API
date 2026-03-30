<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Team;
use Illuminate\Database\Seeder;

class FormulaOneSeeder extends Seeder
{
    /**
     * Seed actuele Formula 1 teams en drivers (2026 grid).
     */
    public function run(): void
    {
        $teams = [
            ['name' => 'Mercedes', 'base_country' => 'Germany', 'team_principal' => 'Toto Wolff'],
            ['name' => 'Ferrari', 'base_country' => 'Italy', 'team_principal' => 'Frederic Vasseur'],
            ['name' => 'McLaren', 'base_country' => 'United Kingdom', 'team_principal' => 'Andrea Stella'],
            ['name' => 'Haas F1 Team', 'base_country' => 'United States', 'team_principal' => 'Ayao Komatsu'],
            ['name' => 'Red Bull Racing', 'base_country' => 'Austria', 'team_principal' => 'Laurent Mekies'],
            ['name' => 'Racing Bulls', 'base_country' => 'Italy', 'team_principal' => 'Alan Permane'],
            ['name' => 'Alpine', 'base_country' => 'France', 'team_principal' => 'Oliver Oakes'],
            ['name' => 'Audi', 'base_country' => 'Germany', 'team_principal' => 'Mattia Binotto'],
            ['name' => 'Williams', 'base_country' => 'United Kingdom', 'team_principal' => 'James Vowles'],
            ['name' => 'Aston Martin', 'base_country' => 'United Kingdom', 'team_principal' => 'Adrian Newey'],
            ['name' => 'Cadillac', 'base_country' => 'United States', 'team_principal' => 'Graeme Lowdon'],
        ];

        foreach ($teams as $teamData) {
            Team::updateOrCreate(
                ['name' => $teamData['name']],
                $teamData
            );
        }

        $teamIds = Team::query()->pluck('id', 'name');

        $drivers = [
            ['team_name' => 'Mercedes', 'first_name' => 'George', 'last_name' => 'Russell', 'number' => 63, 'nationality' => 'United Kingdom', 'date_of_birth' => '1998-02-15'],
            ['team_name' => 'Mercedes', 'first_name' => 'Kimi', 'last_name' => 'Antonelli', 'number' => 12, 'nationality' => 'Italy', 'date_of_birth' => '2006-08-25'],
            ['team_name' => 'Ferrari', 'first_name' => 'Charles', 'last_name' => 'Leclerc', 'number' => 16, 'nationality' => 'Monaco', 'date_of_birth' => '1997-10-16'],
            ['team_name' => 'Ferrari', 'first_name' => 'Lewis', 'last_name' => 'Hamilton', 'number' => 44, 'nationality' => 'United Kingdom', 'date_of_birth' => '1985-01-07'],
            ['team_name' => 'McLaren', 'first_name' => 'Lando', 'last_name' => 'Norris', 'number' => 1, 'nationality' => 'United Kingdom', 'date_of_birth' => '1999-11-13'],
            ['team_name' => 'McLaren', 'first_name' => 'Oscar', 'last_name' => 'Piastri', 'number' => 81, 'nationality' => 'Australia', 'date_of_birth' => '2001-04-06'],
            ['team_name' => 'Haas F1 Team', 'first_name' => 'Esteban', 'last_name' => 'Ocon', 'number' => 31, 'nationality' => 'France', 'date_of_birth' => '1996-09-17'],
            ['team_name' => 'Haas F1 Team', 'first_name' => 'Oliver', 'last_name' => 'Bearman', 'number' => 87, 'nationality' => 'United Kingdom', 'date_of_birth' => '2005-05-08'],
            ['team_name' => 'Red Bull Racing', 'first_name' => 'Max', 'last_name' => 'Verstappen', 'number' => 3, 'nationality' => 'Netherlands', 'date_of_birth' => '1997-09-30'],
            ['team_name' => 'Red Bull Racing', 'first_name' => 'Isack', 'last_name' => 'Hadjar', 'number' => 6, 'nationality' => 'France', 'date_of_birth' => '2004-09-28'],
            ['team_name' => 'Racing Bulls', 'first_name' => 'Liam', 'last_name' => 'Lawson', 'number' => 30, 'nationality' => 'New Zealand', 'date_of_birth' => '2002-02-11'],
            ['team_name' => 'Racing Bulls', 'first_name' => 'Arvid', 'last_name' => 'Lindblad', 'number' => 26, 'nationality' => 'United Kingdom', 'date_of_birth' => '2007-08-08'],
            ['team_name' => 'Alpine', 'first_name' => 'Pierre', 'last_name' => 'Gasly', 'number' => 10, 'nationality' => 'France', 'date_of_birth' => '1996-02-07'],
            ['team_name' => 'Alpine', 'first_name' => 'Franco', 'last_name' => 'Colapinto', 'number' => 43, 'nationality' => 'Argentina', 'date_of_birth' => '2003-05-27'],
            ['team_name' => 'Audi', 'first_name' => 'Nico', 'last_name' => 'Hulkenberg', 'number' => 27, 'nationality' => 'Germany', 'date_of_birth' => '1987-08-19'],
            ['team_name' => 'Audi', 'first_name' => 'Gabriel', 'last_name' => 'Bortoleto', 'number' => 5, 'nationality' => 'Brazil', 'date_of_birth' => '2004-10-14'],
            ['team_name' => 'Williams', 'first_name' => 'Carlos', 'last_name' => 'Sainz', 'number' => 55, 'nationality' => 'Spain', 'date_of_birth' => '1994-09-01'],
            ['team_name' => 'Williams', 'first_name' => 'Alexander', 'last_name' => 'Albon', 'number' => 23, 'nationality' => 'Thailand', 'date_of_birth' => '1996-03-23'],
            ['team_name' => 'Aston Martin', 'first_name' => 'Fernando', 'last_name' => 'Alonso', 'number' => 14, 'nationality' => 'Spain', 'date_of_birth' => '1981-07-29'],
            ['team_name' => 'Aston Martin', 'first_name' => 'Lance', 'last_name' => 'Stroll', 'number' => 18, 'nationality' => 'Canada', 'date_of_birth' => '1998-10-29'],
            ['team_name' => 'Cadillac', 'first_name' => 'Valtteri', 'last_name' => 'Bottas', 'number' => 77, 'nationality' => 'Finland', 'date_of_birth' => '1989-08-28'],
            ['team_name' => 'Cadillac', 'first_name' => 'Sergio', 'last_name' => 'Pérez', 'number' => 11, 'nationality' => 'Mexico', 'date_of_birth' => '1990-01-26'],
        ];

        foreach ($drivers as $driverData) {
            Driver::updateOrCreate(
                ['number' => $driverData['number']],
                [
                    'team_id' => $teamIds[$driverData['team_name']] ?? null,
                    'first_name' => $driverData['first_name'],
                    'last_name' => $driverData['last_name'],
                    'number' => $driverData['number'],
                    'nationality' => $driverData['nationality'],
                    'date_of_birth' => $driverData['date_of_birth'],
                ]
            );
        }
    }
}