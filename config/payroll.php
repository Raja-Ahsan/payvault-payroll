<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FICA (employee share used for withholding; employer matches in this app)
    |--------------------------------------------------------------------------
    */
    'social_security_employee_rate' => 0.062,
    'medicare_employee_rate' => 0.0145,
    'social_security_wage_base' => 176100.00,

    /*
    |--------------------------------------------------------------------------
    | Employer unemployment (placeholder — replace with state tables later)
    |--------------------------------------------------------------------------
    */
    'employer_state_unemployment_rate' => 0.117,
    'employer_federal_unemployment_rate' => 0.006,
    'federal_unemployment_wage_base' => 7000.00,
];
