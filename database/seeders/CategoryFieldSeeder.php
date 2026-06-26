<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryField;
use Illuminate\Database\Seeder;

class CategoryFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Bug' => [
                [
                    'name' => 'application_name',
                    'label' => 'Application Name',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Helpdesk Portal',
                ],
                [
                    'name' => 'application_version',
                    'label' => 'Application Version',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. v1.2.0',
                ],
                [
                    'name' => 'steps_to_reproduce',
                    'label' => 'Steps to Reproduce',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => "1. Open login page\n2. Type invalid username\n3. Click login button",
                ],
                [
                    'name' => 'expected_result',
                    'label' => 'Expected Result',
                    'type' => 'textarea',
                    'is_required' => false,
                    'placeholder' => 'Shows alert "Invalid credentials"',
                ],
                [
                    'name' => 'actual_result',
                    'label' => 'Actual Result',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Shows raw 500 error page',
                ],
                [
                    'name' => 'screenshot',
                    'label' => 'Screenshot',
                    'type' => 'file',
                    'is_required' => false,
                ],
            ],
            'Feature Request' => [
                [
                    'name' => 'feature_name',
                    'label' => 'Feature Name',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Export to Excel',
                ],
                [
                    'name' => 'feature_description',
                    'label' => 'Feature Description',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Describe the feature details...',
                ],
                [
                    'name' => 'business_benefit',
                    'label' => 'Business Benefit',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Describe the benefits for the company/users...',
                ],
                [
                    'name' => 'priority',
                    'label' => 'Priority',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => 'Low,Medium,High',
                ],
            ],
            'Technical Issue' => [
                [
                    'name' => 'affected_system',
                    'label' => 'Affected System',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. ERP Portal, Mail Server',
                ],
                [
                    'name' => 'error_message',
                    'label' => 'Error Message',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Paste the error message or log trace here...',
                ],
                [
                    'name' => 'incident_time',
                    'label' => 'Incident Time',
                    'type' => 'datetime-local',
                    'is_required' => true,
                ],
                [
                    'name' => 'screenshot',
                    'label' => 'Screenshot',
                    'type' => 'file',
                    'is_required' => false,
                ],
            ],
            'Billing' => [
                [
                    'name' => 'invoice_number',
                    'label' => 'Invoice Number',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. INV-2026-0012',
                ],
                [
                    'name' => 'transaction_date',
                    'label' => 'Transaction Date',
                    'type' => 'date',
                    'is_required' => true,
                ],
                [
                    'name' => 'payment_method',
                    'label' => 'Payment Method',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => 'Bank Transfer,Credit Card,E-Wallet,Cash',
                ],
                [
                    'name' => 'proof_of_payment',
                    'label' => 'Proof of Payment',
                    'type' => 'file',
                    'is_required' => true,
                ],
            ],
            'General Inquiry' => [
                [
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'What is your question about?',
                ],
                [
                    'name' => 'question_detail',
                    'label' => 'Question Detail',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Describe your question in detail...',
                ],
            ],
            'Hardware' => [
                [
                    'name' => 'device_type',
                    'label' => 'Device Type',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Laptop, Printer, IP Phone',
                ],
                [
                    'name' => 'brand_model',
                    'label' => 'Brand / Model',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. ThinkPad T14, Epson L3110',
                ],
                [
                    'name' => 'serial_number',
                    'label' => 'Serial Number',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. S/N 1234567890',
                ],
                [
                    'name' => 'device_location',
                    'label' => 'Device Location',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Meeting Room 2nd Floor, IT Room',
                ],
                [
                    'name' => 'damage_description',
                    'label' => 'Damage Description',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Describe the hardware damage or problem...',
                ],
                [
                    'name' => 'device_photo',
                    'label' => 'Device Photo',
                    'type' => 'file',
                    'is_required' => false,
                ],
            ],
            'Software' => [
                [
                    'name' => 'software_name',
                    'label' => 'Software Name',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Adobe Acrobat, Microsoft Office',
                ],
                [
                    'name' => 'software_version',
                    'label' => 'Software Version',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. v2024.1',
                ],
                [
                    'name' => 'operating_system',
                    'label' => 'Operating System',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Windows 11, macOS Sequoia',
                ],
                [
                    'name' => 'error_message',
                    'label' => 'Error Message',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Describe the error message or log...',
                ],
                [
                    'name' => 'screenshot',
                    'label' => 'Screenshot',
                    'type' => 'file',
                    'is_required' => false,
                ],
            ],
            'Network' => [
                [
                    'name' => 'connection_type',
                    'label' => 'Connection Type',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => 'LAN,WiFi,VPN',
                ],
                [
                    'name' => 'location',
                    'label' => 'Location',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Server Room, Finance Office',
                ],
                [
                    'name' => 'affected_device',
                    'label' => 'Affected Device',
                    'type' => 'text',
                    'is_required' => true,
                    'placeholder' => 'e.g. Cisco Switch, Access Point Office',
                ],
                [
                    'name' => 'incident_time',
                    'label' => 'Incident Time',
                    'type' => 'datetime-local',
                    'is_required' => true,
                ],
                [
                    'name' => 'error_description',
                    'label' => 'Error Description',
                    'type' => 'textarea',
                    'is_required' => true,
                    'placeholder' => 'Describe the network problem...',
                ],
            ],
        ];

        foreach ($data as $categoryName => $fields) {
            $category = Category::where('name', $categoryName)->first();
            if ($category) {
                foreach ($fields as $field) {
                    CategoryField::updateOrCreate(
                        [
                            'category_id' => $category->id,
                            'name' => $field['name'],
                        ],
                        [
                            'label' => $field['label'],
                            'type' => $field['type'],
                            'is_required' => $field['is_required'],
                            'options' => $field['options'] ?? null,
                            'placeholder' => $field['placeholder'] ?? null,
                        ]
                    );
                }
            }
        }
    }
}
