<?php

function getDatabaseConfig(): array
{
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=localhost:3306;dbname=siashaf_test",
                "username" => "siashaf25",
                "password" => "siashaf25",
            ],
            "prod" => [
                "url" => "mysql:host=localhost:3306;dbname=siashaf",
                "username" => "siashaf25",
                "password" => "siashaf25",
            ]
        ]
    ];
}