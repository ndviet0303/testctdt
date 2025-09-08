<?php

namespace Database\Seeders;

use App\Models\University;
use App\Models\School;
use App\Models\Department;
use Illuminate\Database\Seeder;

class PhenikaaOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo Đại học Phenikaa
        $phenikaa = University::updateOrCreate(
            ['code' => 'PNU'], // Tìm theo code
            [
            'name' => 'Đại học Phenikaa',
            'code' => 'PNU',
            'full_name' => 'Đại học Phenikaa - Phenikaa University',
            'description' => 'Đại học Phenikaa là một trường đại học tư thục hàng đầu tại Việt Nam, được thành lập năm 2007.',
            'address' => 'Km 12 Nguyễn Xiển, Thanh Xuân, Hà Nội',
            'phone' => '024 6291 8888',
            'email' => 'info@phenikaa-uni.edu.vn',
            'website' => 'https://phenikaa-uni.edu.vn',
            'established_date' => '2007-11-15',
            'is_active' => true,
            'metadata' => [
                'mission' => 'Đào tạo nguồn nhân lực chất lượng cao, nghiên cứu khoa học và chuyển giao công nghệ',
                'vision' => 'Trở thành đại học nghiên cứu hàng đầu trong khu vực',
            ]
            ]
        );

        // Tạo các Trường
        $schools = [
            [
                'name' => 'Trường Công nghệ thông tin',
                'code' => 'CNTT',
                'full_name' => 'Trường Công nghệ thông tin - School of Information Technology',
                'description' => 'Đào tạo các chuyên ngành về công nghệ thông tin, khoa học máy tính và kỹ thuật phần mềm',
                'dean_name' => 'PGS.TS. Nguyễn Văn A',
                'phone' => '024 6291 8801',
                'email' => 'cntt@phenikaa-uni.edu.vn',
                'established_date' => '2008-01-15',
            ],
            [
                'name' => 'Trường Kỹ thuật',
                'code' => 'KT',
                'full_name' => 'Trường Kỹ thuật - School of Engineering',
                'description' => 'Đào tạo các ngành kỹ thuật cơ khí, điện, điện tử và tự động hóa',
                'dean_name' => 'TS. Trần Thị B',
                'phone' => '024 6291 8802',
                'email' => 'kt@phenikaa-uni.edu.vn',
                'established_date' => '2008-03-20',
            ],
            [
                'name' => 'Trường Kinh tế',
                'code' => 'KTE',
                'full_name' => 'Trường Kinh tế - School of Economics',
                'description' => 'Đào tạo các ngành kinh tế, quản trị kinh doanh và tài chính',
                'dean_name' => 'PGS.TS. Lê Văn C',
                'phone' => '024 6291 8803',
                'email' => 'kte@phenikaa-uni.edu.vn',
                'established_date' => '2009-09-01',
            ],
            [
                'name' => 'Trường Y Dược',
                'code' => 'YD',
                'full_name' => 'Trường Y Dược - School of Medicine and Pharmacy',
                'description' => 'Đào tạo các ngành y khoa, dược học và điều dưỡng',
                'dean_name' => 'GS.TS. Phạm Thị D',
                'phone' => '024 6291 8804',
                'email' => 'yd@phenikaa-uni.edu.vn',
                'established_date' => '2010-05-10',
            ]
        ];

        foreach ($schools as $schoolData) {
            $schoolData['university_id'] = $phenikaa->id;
            $school = School::updateOrCreate(
                ['code' => $schoolData['code'], 'university_id' => $phenikaa->id],
                $schoolData
            );

            // Tạo các Khoa cho từng Trường
            $this->createDepartments($school);
        }
    }

    private function createDepartments($school)
    {
        $departmentsBySchool = [
            'CNTT' => [
                [
                    'name' => 'Khoa Hệ thống thông tin',
                    'code' => 'HTTT',
                    'full_name' => 'Khoa Hệ thống thông tin - Department of Information Systems',
                    'description' => 'Đào tạo chuyên ngành hệ thống thông tin, phân tích và thiết kế hệ thống',
                    'head_name' => 'TS. Hoàng Văn X',
                    'phone' => '024 6291 8811',
                    'email' => 'httt@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 3, Tòa A',
                    'student_count' => 450,
                    'staff_count' => 25,
                ],
                [
                    'name' => 'Khoa Khoa học máy tính',
                    'code' => 'KHMT',
                    'full_name' => 'Khoa Khoa học máy tính - Department of Computer Science',
                    'description' => 'Đào tạo về thuật toán, trí tuệ nhân tạo, machine learning',
                    'head_name' => 'PGS.TS. Nguyễn Thị Y',
                    'phone' => '024 6291 8812',
                    'email' => 'khmt@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 4, Tòa A',
                    'student_count' => 380,
                    'staff_count' => 22,
                ],
                [
                    'name' => 'Khoa Kỹ thuật phần mềm',
                    'code' => 'KTPM',
                    'full_name' => 'Khoa Kỹ thuật phần mềm - Department of Software Engineering',
                    'description' => 'Đào tạo về phát triển phần mềm, quản lý dự án phần mềm',
                    'head_name' => 'TS. Lê Minh Z',
                    'phone' => '024 6291 8813',
                    'email' => 'ktpm@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 5, Tòa A',
                    'student_count' => 520,
                    'staff_count' => 28,
                ]
            ],
            'KT' => [
                [
                    'name' => 'Khoa Cơ khí',
                    'code' => 'CK',
                    'full_name' => 'Khoa Cơ khí - Department of Mechanical Engineering',
                    'description' => 'Đào tạo kỹ thuật cơ khí, chế tạo máy, thiết kế cơ khí',
                    'head_name' => 'PGS.TS. Trần Văn M',
                    'phone' => '024 6291 8821',
                    'email' => 'ck@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 2, Tòa B',
                    'student_count' => 320,
                    'staff_count' => 20,
                ],
                [
                    'name' => 'Khoa Điện - Điện tử',
                    'code' => 'DDT',
                    'full_name' => 'Khoa Điện - Điện tử - Department of Electrical and Electronics Engineering',
                    'description' => 'Đào tạo kỹ thuật điện, điện tử, viễn thông',
                    'head_name' => 'TS. Phạm Thị N',
                    'phone' => '024 6291 8822',
                    'email' => 'ddt@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 3, Tòa B',
                    'student_count' => 280,
                    'staff_count' => 18,
                ]
            ],
            'KTE' => [
                [
                    'name' => 'Khoa Quản trị kinh doanh',
                    'code' => 'QTKD',
                    'full_name' => 'Khoa Quản trị kinh doanh - Department of Business Administration',
                    'description' => 'Đào tạo quản trị kinh doanh, marketing, nhân sự',
                    'head_name' => 'PGS.TS. Vũ Thị P',
                    'phone' => '024 6291 8831',
                    'email' => 'qtkd@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 1, Tòa C',
                    'student_count' => 420,
                    'staff_count' => 24,
                ],
                [
                    'name' => 'Khoa Tài chính - Kế toán',
                    'code' => 'TCKT',
                    'full_name' => 'Khoa Tài chính - Kế toán - Department of Finance and Accounting',
                    'description' => 'Đào tạo tài chính ngân hàng, kế toán, kiểm toán',
                    'head_name' => 'TS. Đỗ Văn Q',
                    'phone' => '024 6291 8832',
                    'email' => 'tckt@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 2, Tòa C',
                    'student_count' => 350,
                    'staff_count' => 21,
                ]
            ],
            'YD' => [
                [
                    'name' => 'Khoa Y khoa',
                    'code' => 'YK',
                    'full_name' => 'Khoa Y khoa - Department of Medicine',
                    'description' => 'Đào tạo bác sĩ đa khoa, chuyên khoa',
                    'head_name' => 'GS.TS. Nguyễn Văn R',
                    'phone' => '024 6291 8841',
                    'email' => 'yk@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 1, Tòa D',
                    'student_count' => 200,
                    'staff_count' => 35,
                ],
                [
                    'name' => 'Khoa Dược học',
                    'code' => 'DH',
                    'full_name' => 'Khoa Dược học - Department of Pharmacy',
                    'description' => 'Đào tạo dược sĩ, nghiên cứu dược phẩm',
                    'head_name' => 'PGS.TS. Trần Thị S',
                    'phone' => '024 6291 8842',
                    'email' => 'dh@phenikaa-uni.edu.vn',
                    'office_location' => 'Tầng 2, Tòa D',
                    'student_count' => 150,
                    'staff_count' => 20,
                ]
            ]
        ];

        $departments = $departmentsBySchool[$school->code] ?? [];
        
        foreach ($departments as $deptData) {
            $deptData['school_id'] = $school->id;
            $deptData['established_date'] = '2010-01-01';
            $deptData['is_active'] = true;
            $deptData['metadata'] = [
                'programs' => ['Đại học', 'Thạc sĩ'],
                'research_areas' => ['Nghiên cứu cơ bản', 'Nghiên cứu ứng dụng']
            ];
            
            Department::updateOrCreate(
                ['code' => $deptData['code'], 'school_id' => $school->id],
                $deptData
            );
        }
    }
}
