<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * --------------------------------------------------------------------------
 * Signature
 * --------------------------------------------------------------------------
 * Represents a digital signature linked to a specific document.
 *
 * Responsibilities:
 *   • Store information about a signature (user, document, hash, timestamp)
 *   • Automatically generate a unique signature hash upon creation
 *   • Provide convenient access to related document and user
 *
 * Attributes:
 *   - id: integer, primary key
 *   - document_id: integer, foreign key to Document
 *   - user_id: integer, foreign key to User
 *   - signature_hash: string, unique UUID representing the signature
 *   - signed_at: timestamp, when the signature was created
 *   - created_at / updated_at: timestamps
 *
 * Relationships:
 *   - document(): BelongsTo → the document that owns this signature
 *   - user(): BelongsTo → the user who created the signature
 *
 * Behavior:
 *   - Automatically generates `signature_hash` and sets `signed_at` if not provided
 *
 * Usage Example:
 *   $signature = Signature::create([
 *       'document_id' => 1,
 *       'user_id' => 2,
 *   ]);
 *   echo $signature->signature_hash; // auto-generated UUID
 *
 * @package App\Models
 */
class Signature extends Model
{
    use HasFactory;

    /**
     * ----------------------------------------------------------------------
     * Table Name
     * ----------------------------------------------------------------------
     * Explicitly defines the table associated with this model.
     *
     * @var string
     */
    protected $table = 'signatures';

    /**
     * ----------------------------------------------------------------------
     * Mass Assignable Attributes
     * ----------------------------------------------------------------------
     * Fields allowed for mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_id',
        'user_id',
        'signature_hash',
        'signed_at',
    ];

    /**
     * ----------------------------------------------------------------------
     * Attribute Casting
     * ----------------------------------------------------------------------
     * Casts attributes to native types for convenience.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * ----------------------------------------------------------------------
     * Boot Method
     * ----------------------------------------------------------------------
     * Automatically generates a unique signature hash (UUID) and sets the
     * signed_at timestamp when creating a new signature record.
     */
    protected static function booted()
    {
        static::creating(function ($signature) {
            if (empty($signature->signature_hash)) {
                $signature->signature_hash = (string) Str::uuid();
            }

            if (empty($signature->signed_at)) {
                $signature->signed_at = now();
            }
        });
    }

    /**
     * ----------------------------------------------------------------------
     * Document Relationship
     * ----------------------------------------------------------------------
     * Inverse one-to-many relationship: a signature belongs to a single document.
     *
     * @return BelongsTo
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * ----------------------------------------------------------------------
     * User Relationship
     * ----------------------------------------------------------------------
     * Inverse one-to-many relationship: a signature belongs to a single user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
