<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * --------------------------------------------------------------------------
 * Document
 * --------------------------------------------------------------------------
 * Represents a document that can receive digital signatures.
 *
 * Responsibilities:
 *   • Store basic document information (title, file path, status)
 *   • Track creator (user) and associated company
 *   • Manage related digital signatures
 *
 * Attributes:
 *   - id: integer, primary key
 *   - title: string, document title
 *   - file_path: string, path to the uploaded PDF
 *   - company_id: integer, foreign key to Company
 *   - user_id: integer, foreign key to User (creator)
 *   - status: string, document status (pending, signed, rejected)
 *   - created_at / updated_at: timestamps
 *
 * Relationships:
 *   - signatures(): HasMany → all signatures related to this document
 *   - user(): BelongsTo → the creator of the document
 *   - company(): BelongsTo → the company associated with the document
 *
 * Usage Example:
 *   $document = Document::with('signatures.user')->find(1);
 *   foreach ($document->signatures as $signature) {
 *       echo $signature->user->name;
 *   }
 *
 * @package App\Models
 */
class Document extends Model
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
    protected $table = 'documents';

    /**
     * ----------------------------------------------------------------------
     * Mass Assignable Attributes
     * ----------------------------------------------------------------------
     * Fields allowed for mass assignment via create() or update().
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'file_path',
        'company_id',
        'user_id',
        'status',
    ];

    /**
     * ----------------------------------------------------------------------
     * Signatures Relationship
     * ----------------------------------------------------------------------
     * One-to-many relationship: a document can have multiple digital
     * signatures associated with it.
     *
     * @return HasMany
     */
    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }

    /**
     * ----------------------------------------------------------------------
     * Creator (User) Relationship
     * ----------------------------------------------------------------------
     * Defines the relationship to the user who created the document.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ----------------------------------------------------------------------
     * Company Relationship
     * ----------------------------------------------------------------------
     * Defines the relationship to the company that owns or is associated
     * with the document.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
