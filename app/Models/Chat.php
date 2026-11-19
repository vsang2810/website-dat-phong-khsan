<?php
declare(strict_types=1);

final class Chat extends Model
{
    public function createInquiry(string $guestName, string $guestEmail, string $message, ?int $userId = null): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO chat_conversations (user_id, guest_name, guest_email, status)
                 VALUES (:user_id, :guest_name, :guest_email, 'open')"
            );
            $stmt->execute([
                'user_id' => $userId,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
            ]);
            $convId = (int)$this->db->lastInsertId();

            $msgStmt = $this->db->prepare(
                "INSERT INTO chat_messages (conversation_id, sender_id, sender_type, message, is_read)
                 VALUES (:conv_id, :sender_id, 'customer', :message, 0)"
            );
            $msgStmt->execute([
                'conv_id' => $convId,
                'sender_id' => $userId,
                'message' => $message,
            ]);

            $this->db->commit();
            return $convId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}
