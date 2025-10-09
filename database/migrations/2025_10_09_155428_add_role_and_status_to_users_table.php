public function up(): void {
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('user'); // admin, staff, user
        $table->boolean('is_active')->default(true);
    });
}
public function down(): void {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role','is_active']);
    });
}
