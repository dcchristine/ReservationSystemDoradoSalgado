<div class="admin-header">
    <h2>Contact Messages</h2>
    <div class="admin-user">
        <div class="admin-avatar"><?php echo e(strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1))); ?></div>
        <span><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></span>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Messages (<?php echo count($contacts); ?>)</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php if (empty($contacts)): ?>
                <tr><td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">No messages yet</td></tr>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?php echo (int) $contact['id']; ?></td>
                    <td><strong><?php echo e($contact['name']); ?></strong></td>
                    <td><?php echo e($contact['email']); ?></td>
                    <td><?php echo e($contact['subject'] ?: '-'); ?></td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo e($contact['message']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                    <td><span class="badge badge-<?php echo $contact['is_read'] ? 'read' : 'unread'; ?>"><?php echo $contact['is_read'] ? 'Read' : 'Unread'; ?></span></td>
                    <td>
                        <?php if (!$contact['is_read']): ?>
                            <a href="<?php echo base_url('admin/contacts.php?mark_read=' . (int) $contact['id']); ?>" class="btn-edit"><i class="fas fa-check"></i> Mark Read</a>
                        <?php else: ?>
                            <span style="color:var(--text-light);font-size:0.8rem;">Read</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
