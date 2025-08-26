<?php

namespace Modules\CRMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CRMS\Models\CRMSBusinessNature;
use Modules\CRMS\Models\CRMSCompany;
use Modules\CRMS\Models\CRMSPeople;
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
        // Seed predefined occupations
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
            CRMSPeopleOccupation::firstOrCreate(['name' => $occupation]);
        }

        // Seed predefined races
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
            CRMSPeopleRace::firstOrCreate(['name' => $name]);
        }

        // Seed predefined corporate groups
        $corporateGroups = [
            "Teck Guan Group",
            "Sime Darby",
            "IOI",
            "Sawit Kinabalu",
            "Hap Seng",
            "TSH",
        ];

        foreach ($corporateGroups as $corporateGroup) {
            CRMSCompany::firstOrCreate(['company_name' => $corporateGroup]);
        }

        // Seed predefined business natures
        $businessNatures = [
            "Information Technology Services",
            "Retail and Trading",
            "Construction and Engineering",
            "Logistics and Transportation",
            "Food and Beverage Services",
        ];

        foreach ($businessNatures as $businessNature) {
            CRMSBusinessNature::firstOrCreate(['name' => $businessNature]);
        }

        // Seed predefined income brackets
        $peopleIncomes = [
            "< RM 4000",
            "RM 4001 < RM 8000",
            "RM 8001 < RM 15000",
            "> RM 15000",
        ];

        foreach ($peopleIncomes as $peopleIncome) {
            CRMSPeopleIncome::firstOrCreate(['name' => $peopleIncome]);
        }

        CRMSPeople::create([
            // Relations (assuming these IDs exist in their respective tables)
            'hrms_staff_id' => 1,
            'crms_company_id' => 1,
            'crms_people_race_id' => 1,
            'crms_people_income_id' => 1,
            'crms_people_occupation_id' => 1,
            'crms_business_nature_id' => 1,

            // Required Personal Details
            'customer_name' => 'John Doe',
            'owner_phone' => '60123456789',
            'primary_address' => '123 Jalan Telipok',
            'primary_postcode' => '50450',
            'primary_city' => 'Kota Kinabalu',
            'primary_state' => 'Wilayah Persekutuan Kota Kinabalu',
            'primary_country' => 'Sabah',

            // Optional Fields
            'people_type' => 'Contact',
            'people_status' => 'Active',
            'grading' => 'A',
            'last_contact_date' => '2025-08-26',
            'under_company_registration' => false,
            'id_type' => 'MyKad',
            'id_number' => '850101-14-5555',
            'tin' => 'TIN12345',
            'dob' => '1985-01-01',
            'home_no' => '03-12345678',
            'email' => 'john.doe@example.com',
            'contact_person_name' => null, // or a string value
            'contact_person_phone' => null,
            'office_no' => null,
            'fax_no' => null,
            'postal_address' => 'PO Box 123',
            'postal_postcode' => '50450',
            'postal_city' => 'Kuala Lumpur',
            'postal_state' => 'Wilayah Persekutuan Kuala Lumpur',
            'postal_country' => 'Malaysia',
            'zone' => 'Central',
            'religion' => 'Islam',
            'marital_status' => 'Married',
            'is_corporate' => false,
            'lifestyle_interest' => 'Outdoor activities, sports cars',
            'link_customer' => null,
            'account_terms' => 'Net 30',
            'price_scheme' => 'Standard',
        ]);


        CRMSPeople::create([
            // Relations (assuming these IDs exist in their respective tables)
            'hrms_staff_id' => 1,
            'crms_company_id' => 1,
            'crms_people_race_id' => 1,
            'crms_people_income_id' => 1,
            'crms_people_occupation_id' => 1,
            'crms_business_nature_id' => 1,

            // Required Personal Details
            'customer_name' => 'Feinz Ineffa Mains',
            'owner_phone' => '60123456789',
            'primary_address' => '123 Jalan Madang',
            'primary_postcode' => '50450',
            'primary_city' => 'Kota Kinabalu',
            'primary_state' => 'Wilayah Persekutuan Kota Kinabalu',
            'primary_country' => 'Sabah',

            // Optional Fields
            'people_type' => 'Prospect',
            'people_status' => 'Active',
            'grading' => 'C',
            'last_contact_date' => '2025-08-26',
            'under_company_registration' => false,
            'id_type' => 'MyKad',
            'id_number' => '850101-14-4444',
            'tin' => 'TIN12345',
            'dob' => '1991-01-01',
            'home_no' => '03-12345678',
            'email' => 'john.doe@example.com',
            'contact_person_name' => null, // or a string value
            'contact_person_phone' => null,
            'office_no' => null,
            'fax_no' => null,
            'postal_address' => 'PO Box 123',
            'postal_postcode' => '50450',
            'postal_city' => 'Kuala Lumpur',
            'postal_state' => 'Wilayah Persekutuan Kuala Lumpur',
            'postal_country' => 'Malaysia',
            'zone' => 'Central',
            'religion' => 'Islam',
            'marital_status' => 'Married',
            'is_corporate' => false,
            'lifestyle_interest' => 'Outdoor activities, sports cars',
            'link_customer' => null,
            'account_terms' => 'Net 30',
            'price_scheme' => 'Standard',
        ]);
    }
}
