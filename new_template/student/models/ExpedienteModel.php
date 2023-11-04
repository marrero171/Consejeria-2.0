<?php
// models/LoginModel.php
class StudentModel
{
    public function getStudentInfo($conn, $student_num)
    {
        $sql = "SELECT email, name1, name2, last_name1, last_name2
                FROM student
                WHERE student.student_num = ?";

        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param("s", $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentInfo = $result->fetch_assoc();
        if ($studentInfo['name2'] != null) {
            $studentName = $studentInfo['name1'] . " " . $studentInfo['name2'] . " " . $studentInfo['last_name1'] . " " . $studentInfo['last_name2'];
        } else
            $studentName = $studentInfo['name1'] . " " . $studentInfo['last_name1'] . " " . $studentInfo['last_name2'];

        $studentInfo['full_student_name'] = $studentName;
        $formatted_student_num = substr($student_num, 0, 3) . '-' . substr($student_num, 3, 2) . '-' . substr($student_num, 5);
        $studentInfo['formatted_student_num'] = $formatted_student_num;

        return $studentInfo;
    }

    public function getStudentCCOMCourses($conn, $student_num)
    {
        $sql = "SELECT ccom_courses.crse_code, ccom_courses.name, ccom_courses.credits, student_courses.crse_grade, student_courses.crse_status, 
                student_courses.convalidacion, student_courses.equivalencia,  student_courses.term, ccom_courses.type
        FROM ccom_courses
        LEFT JOIN student_courses ON ccom_courses.crse_code = student_courses.crse_code
        AND student_courses.student_num = ? WHERE ccom_courses.type = 'mandatory'";

        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param("s", $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentRecord = [];
        while ($row = $result->fetch_assoc()) {
            $studentRecord[] = $row;
        }
        return $studentRecord;
    }

    public function getStudentGeneralCourses($conn, $student_num)
    {
        $sql = "SELECT general_courses.crse_code, general_courses.name, general_courses.credits, student_courses.crse_grade, student_courses.crse_status, 
                        student_courses.convalidacion, student_courses.equivalencia,  student_courses.term, general_courses.type
                FROM general_courses
                LEFT JOIN student_courses ON general_courses.crse_code = student_courses.crse_code
                AND student_courses.student_num = ?";

        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param("s", $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentRecord = [];
        while ($row = $result->fetch_assoc()) {
            $studentRecord[] = $row;
        }
        return $studentRecord;
    }
}