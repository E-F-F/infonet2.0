<?php

namespace Modules\CRMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CRMS\Models\CRMSBusinessNature;
use Modules\CRMS\Models\CRMSCorporateGroup;
use Modules\CRMS\Models\CRMSPeopleIncome;
use Modules\CRMS\Models\CRMSPeopleOccupation;
use Modules\CRMS\Models\CRMSPeopleRace;

class CRMSDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $occupations = [
            "Accountant",
            "Account Executive",
            "Accounts Clerk",
            "Actor/Actress",
            "Administrative Assistant",
            "Administrative Officer",
            "Architect",
            "Artist",
            "Athlete",
            "Auditor",
            "Baker",
            "Bank Officer",
            "Barber",
            "Barista",
            "Brand Manager",
            "Bus Driver",
            "Business Owner",
            "Business Person",
            "Carpenter",
            "Chef",
            "Chemical Engineer",
            "Chemist",
            "Civil Engineer",
            "Clerk",
            "Consultant",
            "Construction Worker",
            "Cook",
            "Counselor",
            "Customer Service",
            "Cybersecurity Specialist",
            "Data Analyst",
            "Data Scientist",
            "Database Administrator",
            "Dentist",
            "Dietitian",
            "Digital Marketing Specialist",
            "Doctor",
            "Domestic Helper",
            "Driver",
            "Editor",
            "Electrical Engineer",
            "Electrical Technician",
            "Engineer",
            "Entrepreneur",
            "Event Manager",
            "Farmer",
            "Fashion Designer",
            "Financial Analyst",
            "Flight Attendant",
            "Geologist",
            "Government Officer",
            "Graphic Designer",
            "Hairdresser",
            "Heavy Machinery Operator",
            "Homemaker",
            "Hotel Manager",
            "Human Resources Executive",
            "Human Resources Manager",
            "Insurance Agent",
            "IT Support",
            "Journalist",
            "Kindergarten Teacher",
            "Lab Technician",
            "Lecturer",
            "Librarian",
            "Lorry Driver",
            "Manager",
            "Manufacturing Technician",
            "Marketing Executive",
            "Marketing Manager",
            "Mathematician",
            "Mechanic",
            "Mechanical Engineer",
            "Medical Assistant",
            "Military Personnel",
            "Musician",
            "Network Administrator",
            "Nurse",
            "Optician",
            "Others",
            "Personal Assistant",
            "Pharmacist",
            "Physical Therapist",
            "Physiotherapist",
            "Pilot",
            "Plumber",
            "Police Officer",
            "Primary School Teacher",
            "Producer",
            "Production Manager",
            "Production Operator",
            "Production Supervisor",
            "Programmer",
            "Project Engineer",
            "Project Manager",
            "Quality Inspector",
            "Quantity Surveyor",
            "Radio Announcer",
            "Real Estate Agent",
            "Receptionist",
            "Researcher",
            "Restaurant Manager",
            "Retiree",
            "Sales Executive",
            "Sales Manager",
            "Salesperson",
            "Scientist",
            "Secondary School Teacher",
            "Secretary",
            "Singer",
            "Site Manager",
            "Social Media Manager",
            "Software Developer",
            "Software Engineer",
            "Student",
            "Surgeon",
            "Surveyor",
            "System Analyst",
            "Taxi/e-hailing Driver",
            "Teacher",
            "Technician",
            "Trader",
            "Travel Agent",
            "Tutor",
            "UI/UX Designer",
            "Unemployed",
            "Veterinarian",
            "Videographer",
            "Waiter/Waitress",
            "Web Developer",
            "Writer",
        ];

        foreach ($occupations as $occupation) {
            CRMSPeopleOccupation::create([
                'name' => $occupation,
                'is_active' => true,
            ]);
        }

        $raceNames = [
            'CHINESE',
            'MALAY',
            'INDIAN',
            'KADAZAN - DUSUN',
            'SINO KADAZAN',
            'IBAN',
            'UM - GENERAL RACE',
        ];

        foreach ($raceNames as $name) {
            CRMSPeopleRace::create([
                'name' => $name,
                'is_active' => true,
            ]);
        }

        $corporateGroups = [
            "Teck Guan Group",
            "Sime Darby",
            "IOI",
            "Sawit Kinabalu",
            "Hap Seng",
            "TSH"
        ];

        foreach ($corporateGroups as $corporateGroup) {
            CRMSCorporateGroup::create([
                'name' => $corporateGroup,
                'is_active' => true,
            ]);
        }

        $businessNatures = [
            "Information Technology Services",
            "Retail and Trading",
            "Construction and Engineering",
            "Logistics and Transportation",
            "Food and Beverage Services",
        ];

        foreach ($businessNatures as $businessNature) {
            CRMSBusinessNature::create([
                'name' => $businessNature,
                'is_active' => true,
            ]);
        }


        $peopleIncomes = [
            "< RM 4000",
            "RM 4001 < RM 8000",
            "RM 8001 < RM 15000",
            "> RM 15000",
        ];

        foreach ($peopleIncomes as $peopleIncome) {
            CRMSPeopleIncome::create([
                'name' => $peopleIncome,
                'is_active' => true,
            ]);
        }
    }
}
