<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$page_title = "Schedule Management";
include('dbcon.php');
include('header.php');
?>

<div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-calendar3"></i> Schedule Management
            </h4>
            <a href="schedule-create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Schedule
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Semester</th>
                            <th>Academic Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT sub.subject_id, sub.subject_name,
                                        t.id as teacher_id, t.name as teacher_name,
                                        s.schedule_id, s.day_of_week, s.start_time, s.end_time,
                                        s.room, s.semester, s.academic_year
                                 FROM subjects sub
                                 CROSS JOIN teachers t
                                 LEFT JOIN schedules s ON s.subject_id = sub.subject_id AND s.teacher_id = t.id
                                 ORDER BY sub.subject_name, t.name";
                        $result = mysqli_query($con, $query);

                        if ($result) {
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($row['teacher_name']) ?></td>
                                    <td>
                                        <?php if (isset($row['day_of_week'])): ?>
                                            <span class="badge bg-info"><?= $days[$row['day_of_week']] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not scheduled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['start_time']) && !empty($row['end_time'])): ?>
                                            <span class="badge bg-light text-dark">
                                                <?= date('h:i A', strtotime($row['start_time'])) ?> -
                                                <?= date('h:i A', strtotime($row['end_time'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not scheduled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['room'])): ?>
                                            <span class="badge bg-light text-dark"><?= htmlspecialchars($row['room']) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= !empty($row['semester']) ? htmlspecialchars($row['semester']) : '<span class="badge bg-secondary">Not set</span>' ?></td>
                                    <td><?= !empty($row['academic_year']) ? htmlspecialchars($row['academic_year']) : '<span class="badge bg-secondary">Not set</span>' ?></td>
                                    <td>
                                        <?php if (isset($row['schedule_id'])): ?>
                                            <div class="btn-group" role="group">
                                                <a href="schedule-view.php?id=<?= $row['schedule_id'] ?>" class="btn btn-info btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="schedule-edit.php?id=<?= $row['schedule_id'] ?>" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-danger btn-sm delete-schedule"
                                                        data-id="<?= $row['schedule_id'] ?>"
                                                        data-subject="<?= htmlspecialchars($row['subject_name']) ?>"
                                                        data-teacher="<?= htmlspecialchars($row['teacher_name']) ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <a href="schedule-create.php?subject_id=<?= $row['subject_id'] ?>&teacher_id=<?= $row['teacher_id'] ?>"
                                               class="btn btn-success btn-sm">
                                                <i class="bi bi-plus-circle"></i> Add Schedule
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-schedule');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const scheduleId = this.dataset.id;
            const subjectName = this.dataset.subject;
            const teacherName = this.dataset.teacher;
            
            Swal.fire({
                title: 'Delete Schedule?',
                text: `Are you sure you want to delete the schedule for ${subjectName} with ${teacherName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `schedule-delete.php?id=${scheduleId}`;
                }
            });
        });
    });
});
</script>

<?php include('footer.php'); ?>