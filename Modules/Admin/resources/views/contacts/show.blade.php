@extends('admin::layouts.app')
@section('page_title', 'Message from ' . $contact->name)

@section('page_actions')
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back to Messages
    </a>
@endsection

@section('admin_content')

    <div class="row">

        {{-- LEFT: Chat Column --}}
        <div class="col-md-8">
            <div class="card card-outline card-primary direct-chat direct-chat-primary">

                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-comments mr-2"></i>
                        {{ $contact->subject ?? 'No Subject' }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $contact->status_badge }} px-2 py-1">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </div>
                </div>

                {{-- Chat messages --}}
                <div class="card-body">
                    <div class="direct-chat-messages" style="height: auto; min-height: 260px; overflow: visible;">

                        {{-- Incoming: visitor message --}}
                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">{{ $contact->name }}</span>
                                <span class="direct-chat-timestamp float-right"
                                    title="{{ optional($contact->created_at)->format('d M Y, h:i A') }}">
                                    {{ optional($contact->created_at)->format('d M Y, h:i A') ?? 'No date' }}
                                </span>
                            </div>
                            <div class="direct-chat-img d-flex align-items-center justify-content-center
                                    bg-primary text-white font-weight-bold rounded-circle"
                                style="width:40px;height:40px;font-size:1rem;flex-shrink:0;">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div class="direct-chat-text">
                                {!! nl2br(e($contact->message)) !!}
                            </div>
                        </div>

                        {{-- Outgoing: admin reply --}}
                        @if ($contact->admin_reply)
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right">You (Admin)</span>
                                    <span class="direct-chat-timestamp float-left"
                                        title="{{ $contact->replied_at?->format('d M Y, h:i A') }}">
                                        {{ $contact->replied_at?->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                                <div class="direct-chat-img d-flex align-items-center justify-content-center
                                        bg-success text-white font-weight-bold rounded-circle"
                                    style="width:40px;height:40px;font-size:1rem;flex-shrink:0;">
                                    A
                                </div>
                                <div class="direct-chat-text">
                                    {!! nl2br(e($contact->admin_reply)) !!}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Reply form at the bottom like a chat input --}}
                <div class="card-footer">
                    <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <textarea name="admin_reply" id="replyInput" rows="1"
                                class="form-control @error('admin_reply') is-invalid @enderror"
                                placeholder="Type your reply to {{ $contact->name }}…"
                                style="resize:none;border-radius:0.25rem 0 0 0.25rem;min-height:42px;"
                                oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'">{{ old('admin_reply') }}</textarea>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" style="height:100%;">
                                    <i class="fas fa-paper-plane mr-1"></i> Send
                                </button>
                            </div>
                            @error('admin_reply')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted mt-1 d-block">
                            Reply will be sent to <strong>{{ $contact->email }}</strong>
                        </small>
                    </form>
                </div>

            </div>
        </div>

        {{-- RIGHT: Sidebar --}}
        <div class="col-md-4">

            {{-- Sender Card --}}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-2"></i> Sender
                    </h3>
                </div>
                <div class="card-body box-profile text-center pb-2">
                    <div class="d-inline-flex align-items-center justify-content-center
                            rounded-circle bg-primary text-white font-weight-bold mb-3"
                        style="width:64px;height:64px;font-size:1.6rem;">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <h3 class="profile-username text-center">{{ $contact->name }}</h3>
                    <p class="text-muted text-center mb-0">
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                    </p>
                    @if ($contact->phone)
                        <p class="text-muted text-center mb-0">
                            <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                        </p>
                    @endif
                </div>
                <div class="card-footer p-0">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted pl-3" style="width:90px;">Status</td>
                            <td>
                                <span class="badge badge-{{ $contact->status_badge }}">
                                    {{ ucfirst($contact->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted pl-3">Received</td>
                            <td>{{ $contact->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if ($contact->replied_at)
                            <tr>
                                <td class="text-muted pl-3">Replied</td>
                                <td>{{ $contact->replied_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-2"></i> Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <a href="mailto:{{ $contact->email }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-external-link-alt mr-2"></i> Open in Email Client
                    </a>
                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
                        onsubmit="return confirm('Delete this message permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash mr-2"></i> Delete Message
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection
