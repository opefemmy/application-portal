<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\EmailTemplate;
use App\Models\Role;
use App\Models\Administrator;
use App\Models\FormField;
use App\Models\ApplicationType;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'portal_name' => 'required|string|max:200',
            'institution_name' => 'required|string|max:200',
            'contact_email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'application_prefix' => 'required|string|max:10',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('settings_update', 'Updated general settings');

        return back()->with('success', 'General settings updated successfully.');
    }

    public function updatePortal(Request $request)
    {
        $validated = $request->validate([
            'portal_open_date' => 'nullable|date',
            'portal_close_date' => 'nullable|date|after:portal_open_date',
            'max_applications' => 'nullable|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('settings_update', 'Updated portal settings');

        return back()->with('success', 'Portal settings updated successfully.');
    }

    public function updateUpload(Request $request)
    {
        $validated = $request->validate([
            'max_upload_size' => 'required|integer|min:1024|max:20480',
            'allowed_file_types' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('settings_update', 'Updated upload settings');

        return back()->with('success', 'Upload settings updated successfully.');
    }

    public function updateMaintenance(Request $request)
    {
        $validated = $request->validate([
            'maintenance_mode' => 'required|boolean',
            'maintenance_message' => 'nullable|string',
        ]);

        Setting::set('maintenance_mode', $validated['maintenance_mode']);
        Setting::set('maintenance_message', $validated['maintenance_message'] ?? '');

        ActivityLog::log('settings_update', 'Updated maintenance mode');

        return back()->with('success', 'Maintenance settings updated.');
    }

    // Branding & Rebranding Settings
    public function branding()
    {
        $settings = Setting::getSettings();
        return view('admin.settings.branding', compact('settings'));
    }

    public function updateBranding(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'accent_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'header_text_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'footer_text_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $logoName);
            Setting::set('logo', 'images/' . $logoName);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = 'favicon.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('images'), $faviconName);
            Setting::set('favicon', 'images/' . $faviconName);
        }

        // Handle footer logo upload
        if ($request->hasFile('footer_logo')) {
            $footerLogo = $request->file('footer_logo');
            $footerLogoName = 'footer_logo.' . $footerLogo->getClientOriginalExtension();
            $footerLogo->move(public_path('images'), $footerLogoName);
            Setting::set('footer_logo', 'images/' . $footerLogoName);
        }

        // Handle background image upload
        if ($request->hasFile('login_background')) {
            $bg = $request->file('login_background');
            $bgName = 'login_background.' . $bg->getClientOriginalExtension();
            $bg->move(public_path('images'), $bgName);
            Setting::set('login_background', 'images/' . $bgName);
        }

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('branding_update', 'Updated branding settings');

        return back()->with('success', 'Branding settings updated successfully!');
    }

    public function emailTemplates()
    {
        $templates = EmailTemplate::all();
        return view('admin.settings.email-templates', compact('templates'));
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update($validated);

        ActivityLog::log('email_template_update', "Updated email template: {$template->name}");

        return back()->with('success', 'Email template updated successfully.');
    }

    public function formBuilder()
    {
        $fields = FormField::orderBy('section')->orderBy('sort_order')->get();
        $sections = FormField::distinct()->pluck('section')->toArray();
        return view('admin.settings.form-builder', compact('fields', 'sections'));
    }

    public function storeFormField(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:100',
            'field_name' => 'required|string|max:100',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,select,checkbox,radio,date,file,textarea',
            'is_visible' => 'boolean',
            'is_required' => 'boolean',
            'sort_order' => 'integer',
        ]);

        FormField::create($validated);

        ActivityLog::log('form_builder', "Created new field: {$validated['field_name']}");

        return back()->with('success', 'Field created successfully.');
    }

    public function updateFormField(Request $request, FormField $field)
    {
        $validated = $request->validate([
            'field_label' => 'required|string|max:255',
            'is_visible' => 'boolean',
            'is_required' => 'boolean',
        ]);

        $field->update($validated);

        return back()->with('success', 'Field updated successfully.');
    }

    public function destroyFormField(FormField $field)
    {
        $field->delete();
        return back()->with('success', 'Field deleted successfully.');
    }

    public function roles()
    {
        $roles = Role::all();
        return view('admin.settings.roles', compact('roles'));
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        Role::create($validated);

        ActivityLog::log('role_create', "Created role: {$validated['name']}");

        return back()->with('success', 'Role created successfully.');
    }

    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role->update($validated);

        ActivityLog::log('role_update', "Updated role: {$validated['name']}");

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroyRole(Role $role)
    {
        if ($role->administrators()->count() > 0) {
            return back()->with('error', 'Cannot delete role with administrators.');
        }

        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }

    public function users()
    {
        $users = Administrator::with('role')->orderBy('name')->paginate(20);
        $roles = Role::all();
        return view('admin.settings.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:administrators',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Administrator::create($validated);

        ActivityLog::log('user_create', "Created user: {$validated['name']}");

        return back()->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, Administrator $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('administrators')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        ActivityLog::log('user_update', "Updated user: {$validated['name']}");

        return back()->with('success', 'User updated successfully.');
    }

    public function destroyUser(Administrator $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    // Application Types Management
    public function applicationTypes()
    {
        $types = ApplicationType::orderBy('sort_order')->get();
        return view('admin.settings.application-types', compact('types'));
    }

    public function storeApplicationType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        ApplicationType::create($validated);

        ActivityLog::log('application_type_create', "Created application type: {$validated['name']}");

        return back()->with('success', 'Application type created successfully.');
    }

    public function updateApplicationType(Request $request, ApplicationType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $type->update($validated);

        ActivityLog::log('application_type_update', "Updated application type: {$validated['name']}");

        return back()->with('success', 'Application type updated successfully.');
    }

    public function destroyApplicationType(ApplicationType $type)
    {
        $type->delete();
        return back()->with('success', 'Application type deleted successfully.');
    }

    public function editApplicationTypeFields(ApplicationType $type)
    {
        $formFields = FormField::orderBy('section')->orderBy('sort_order')->get();
        $sections = $formFields->groupBy('section');
        $typeFields = $type->formFields()->get()->keyBy('id');

        return view('admin.settings.application-type-fields', compact('type', 'formFields', 'sections', 'typeFields'));
    }

    public function updateApplicationTypeFields(Request $request, ApplicationType $type)
    {
        $validated = $request->validate([
            'fields' => 'required|array',
            'fields.*.enabled' => 'boolean',
            'fields.*.required' => 'boolean',
            'fields.*.sort_order' => 'integer',
        ]);

        $type->formFields()->detach();

        foreach ($validated['fields'] as $fieldId => $fieldData) {
            if (!empty($fieldData['enabled'])) {
                $type->formFields()->attach($fieldId, [
                    'is_enabled' => true,
                    'is_required' => !empty($fieldData['required']),
                    'sort_order' => $fieldData['sort_order'] ?? 0,
                ]);
            }
        }

        ActivityLog::log('application_type_fields_update', "Updated fields for: {$type->name}");

        return redirect()->route('admin.settings.application-types')->with('success', 'Fields updated successfully.');
    }
}