<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarData;
use App\Models\Particular;
use App\Models\Value;

class BarDataSeeder extends Seeder
{
    public function run(): void
    {
        $jsonData = '{
            "success": true,
            "data": [
                {
                    "name": "Higher Education",
                    "particulars": [
                        {
                            "name": "Outcome Indicator 1: Percentage of first-time licensure exam takers that pass the licensure exam.",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 7, "accomplishment": 7.78},
                                {"year": 2023, "target": 9.7, "accomplishment": 14.46},
                                {"year": 2024, "target": 5.74, "accomplishment": 9.7}
                            ]
                        },
                        {
                            "name": "Outcome Indicator 2: Percentage of graduates (2 years prior) that are employed",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 5, "accomplishment": 1.68},
                                {"year": 2023, "target": 5.95, "accomplishment": 2.34},
                                {"year": 2024, "target": 9.84, "accomplishment": 2.05}
                            ]
                        },
                        {
                            "name": "Output Indicator 1: Percentage of undergraduate students enrolled in CHED-identified and RDC-identified priority programs",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 18, "accomplishment": 13.91},
                                {"year": 2023, "target": 18.62, "accomplishment": 18.62},
                                {"year": 2024, "target": 13.5, "accomplishment": 0}
                            ]
                        },
                        {
                            "name": "Output Indicator 2: Percentage of undergraduate programs with accreditation",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 22.58, "accomplishment": 22.58},
                                {"year": 2023, "target": 22.58, "accomplishment": 18.75},
                                {"year": 2024, "target": 18.75, "accomplishment": 18.75}
                            ]
                        }
                    ]
                },
                {
                    "name": "Research Program",
                    "particulars": [
                        {
                            "name": "Outcome Indicator 1: Member of research outputs in the last years utilized by the industry or by other beneficiaries",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 3, "accomplishment": 5},
                                {"year": 2023, "target": 3, "accomplishment": 6},
                                {"year": 2024, "target": 3, "accomplishment": 3}
                            ]
                        },
                        {
                            "name": "Output Indicator 1: Number of research outputs completed within the year",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 2, "accomplishment": 10},
                                {"year": 2023, "target": 9, "accomplishment": 18},
                                {"year": 2024, "target": 10, "accomplishment": 7}
                            ]
                        },
                        {
                            "name": "Output Indicator 2: Percentage of research outputs published in internationally-refereed or CHED recognized journal within the year",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 1, "accomplishment": 4.71},
                                {"year": 2023, "target": 1, "accomplishment": 2.35},
                                {"year": 2024, "target": 4, "accomplishment": 0.86}
                            ]
                        }
                    ]
                },
                {
                    "name": "Technical Advisory Extension Program",
                    "particulars": [
                        {
                            "name": "Outcome Indicator 1: Number of active partnerships with LGUs, industries, NGOs, NFAs, SMEs, and other stakeholders as a result of extension activities",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 5, "accomplishment": 5},
                                {"year": 2023, "target": 10, "accomplishment": 14},
                                {"year": 2024, "target": 10, "accomplishment": 0}
                            ]
                        },
                        {
                            "name": "Output Indicator 1: Number of trainees weighted by the length of training",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 652, "accomplishment": 41},
                                {"year": 2023, "target": 653, "accomplishment": 1116.25},
                                {"year": 2024, "target": 653, "accomplishment": 0}
                            ]
                        },
                        {
                            "name": "Output Indicator 2: Number of extension programs organized and supported consistent with the SUCs mandated and priority programs",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 9, "accomplishment": 22},
                                {"year": 2023, "target": 7, "accomplishment": 25},
                                {"year": 2024, "target": 7, "accomplishment": 0}
                            ]
                        },
                        {
                            "name": "Output Indicator 3: Percentage of beneficiaries who rate the training course/s as satisfactory or higher in terms of quality and relevance",
                            "values": [
                                {"year": 2020, "target": 0, "accomplishment": 0},
                                {"year": 2021, "target": 0, "accomplishment": 0},
                                {"year": 2022, "target": 8, "accomplishment": 0.462},
                                {"year": 2023, "target": 10, "accomplishment": 10},
                                {"year": 2024, "target": 10, "accomplishment": 0}
                            ]
                        }
                    ]
                }
            ]
        }';

        $data = json_decode($jsonData, true)['data'];

        foreach ($data as $item) {
            $particulars = $item['particulars'];
            unset($item['particulars']);
            $barData = BarData::create([
                'title' => $item['name'],
                'description' => '',
            ]);

            foreach ($particulars as $particular) {
                $values = $particular['values'];
                unset($particular['values']);

                $particular = $barData->particular()->create([
                    'title' => $particular['name'],
                ]);

                foreach ($values as $value) {
                    $particular->values()->create($value);
                }
            }

        }


    }
}