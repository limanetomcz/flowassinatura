{{-- =============================================================================
     Single Signature Card Component
=============================================================================
     Purpose:
       • Display a single signature in a card format.
       • Show user information, signed date, and truncated signature hash.
       • Provide actions (delete).
       • Responsive layout using Bootstrap classes.

     Structure:
       1. Container: responsive column with optional ID for JS targeting.
       2. Card Wrapper: Bootstrap card with shadow, gradient background, and border highlight.
       3. Card Header: displays user name & signed date.
       4. Card Body: displays signature hash and action buttons.
============================================================================= --}}

<div class="col-12 mb-3 d-flex signature-card" id="signature-{{ $signature->id }}">
    
    {{-- -------------------------------------------------------------------------
         1. Card Wrapper
    ------------------------------------------------------------------------- --}}
    <div class="card shadow-sm flex-fill bg-gradient-light border-left-primary">
        
        {{-- ---------------------------------------------------------------------
             2. Card Header: User Info & Signed Date
             ---------------------------------------------------------------------
             Elements:
               • User icon + name (or fallback to user ID)
               • Signed timestamp (formatted)
               • Flex layout for spacing between name and date
        --------------------------------------------------------------------- --}}
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-0">
            <span class="fw-bold text-dark">
                <i class="fas fa-user-check me-2 text-secondary"></i>
                {{ $signature->user->name ?? 'Usuário #' . $signature->user_id }}
            </span>
            <span class="text-muted">
                {{ $signature->signed_at?->format('d/m/Y H:i') ?? '-' }}
            </span>
        </div>

        {{-- ---------------------------------------------------------------------
             3. Card Body: Signature Hash & Actions
             ---------------------------------------------------------------------
             Layout:
               • Left side: truncated signature hash (max 50 chars)
               • Right side: action buttons (delete)
               • Flexbox for responsive alignment
        --------------------------------------------------------------------- --}}
        <div class="card-body d-flex justify-content-between text-dark">
            {{-- Signature Hash --}}
            <div>
                <p class="mb-0">
                    <strong>Hash da assinatura:</strong> {{ Str::limit($signature->signature_hash, 50) }}
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex flex-wrap">
                
                {{-- Delete Action --}}
                <form method="POST" 
                      action="{{ route('admin.documents.signatures.destroy', ['document' => $signature->document_id, 'signature' => $signature->id]) }}" 
                      onsubmit="return confirm('Tem certeza que deseja excluir esta assinatura?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir Assinatura">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                
            </div>
        </div>

    </div>
</div>
