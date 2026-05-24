import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../models/task_model.dart';
import '../providers/auth_provider.dart';
import '../providers/task_provider.dart';
import '../theme.dart';

class AddEditTaskSheet extends StatefulWidget {
  final Task? task;

  const AddEditTaskSheet({super.key, this.task});

  @override
  State<AddEditTaskSheet> createState() => _AddEditTaskSheetState();
}

class _AddEditTaskSheetState extends State<AddEditTaskSheet> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _descController = TextEditingController();
  final _categoryController = TextEditingController();

  late String _selectedPriority;
  DateTime? _selectedDueDate;
  bool _isSaving = false;

  final List<String> _priorities = ['Low', 'Medium', 'High'];

  @override
  void initState() {
    super.initState();
    if (widget.task != null) {
      _titleController.text = widget.task!.title;
      _descController.text = widget.task!.description ?? '';
      _categoryController.text = widget.task!.category;
      _selectedPriority = widget.task!.priority;
      _selectedDueDate = widget.task!.dueDate;
    } else {
      _categoryController.text = 'General';
      _selectedPriority = 'Medium';
      _selectedDueDate = null;
    }
  }

  @override
  void dispose() {
    _titleController.dispose();
    _descController.dispose();
    _categoryController.dispose();
    super.dispose();
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedDueDate ?? DateTime.now(),
      firstDate: DateTime.now().subtract(const Duration(days: 365)),
      lastDate: DateTime.now().add(const Duration(days: 365 * 5)),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: const ColorScheme.light(
              primary: AppColors.primary,
              onPrimary: Colors.white,
              onSurface: AppColors.textDark,
            ),
          ),
          child: child!,
        );
      },
    );
    if (picked != null && picked != _selectedDueDate) {
      setState(() {
        _selectedDueDate = picked;
      });
    }
  }

  Color _getPriorityColor(String priority) {
    switch (priority.toLowerCase()) {
      case 'high':
        return AppColors.priorityHigh;
      case 'medium':
        return AppColors.priorityMedium;
      case 'low':
      default:
        return AppColors.priorityLow;
    }
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isSaving = true;
    });

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final taskProvider = Provider.of<TaskProvider>(context, listen: false);
    final token = authProvider.token;

    if (token == null) {
      setState(() {
        _isSaving = false;
      });
      return;
    }

    try {
      if (widget.task == null) {
        // Add Mode
        await taskProvider.addTask(
          token,
          title: _titleController.text.trim(),
          description: _descController.text.trim(),
          category: _categoryController.text.trim(),
          priority: _selectedPriority,
          dueDate: _selectedDueDate,
        );
      } else {
        // Edit Mode
        await taskProvider.updateTask(
          token,
          widget.task!.id,
          {
            'title': _titleController.text.trim(),
            'description': _descController.text.trim(),
            'category': _categoryController.text.trim(),
            'priority': _selectedPriority,
            'due_date': _selectedDueDate?.toIso8601String().split('T')[0],
          },
        );
      }

      if (mounted) {
        Navigator.of(context).pop();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(widget.task == null
                ? 'Task added successfully! 🎉'
                : 'Task updated successfully! 🎉'),
            backgroundColor: Colors.green,
            behavior: SnackBarBehavior.floating,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error: ${e.toString().replaceAll('Exception: ', '')}'),
            backgroundColor: AppColors.priorityHigh,
            behavior: SnackBarBehavior.floating,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isSaving = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final mediaQuery = MediaQuery.of(context);

    return Container(
      padding: EdgeInsets.only(
        top: 24,
        left: 24,
        right: 24,
        bottom: mediaQuery.viewInsets.bottom + 24,
      ),
      decoration: const BoxDecoration(
        color: AppColors.background,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(28),
          topRight: Radius.circular(28),
        ),
      ),
      child: SingleChildScrollView(
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Bottom sheet handle
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  margin: const EdgeInsets.only(bottom: 20),
                  decoration: BoxDecoration(
                    color: AppColors.textMuted.withValues(alpha: 0.3),
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),

              // Title Header
              Text(
                widget.task == null ? 'Create New Task' : 'Edit Task',
                style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                      fontSize: 24,
                    ),
              ),
              const SizedBox(height: 20),

              // Title Field
              TextFormField(
                controller: _titleController,
                textCapitalization: TextCapitalization.sentences,
                decoration: const InputDecoration(
                  labelText: 'Task Title',
                  prefixIcon: Icon(Icons.title_rounded, color: AppColors.secondary),
                  hintText: 'What needs to be done?',
                ),
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'Please enter a task title';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),

              // Description Field
              TextFormField(
                controller: _descController,
                textCapitalization: TextCapitalization.sentences,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Description (Optional)',
                  prefixIcon: Icon(Icons.description_outlined, color: AppColors.secondary),
                  hintText: 'Add details about this task...',
                ),
              ),
              const SizedBox(height: 20),

              // Category Text Field
              TextFormField(
                controller: _categoryController,
                textCapitalization: TextCapitalization.words,
                decoration: const InputDecoration(
                  labelText: 'Category',
                  prefixIcon: Icon(Icons.category_outlined, color: AppColors.secondary),
                  hintText: 'e.g. Work, Personal, Shopping',
                ),
                validator: (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'Please enter a category';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),

              // Priority Selector Header
              const Text(
                'Priority',
                style: TextStyle(
                  color: AppColors.textDark,
                  fontWeight: FontWeight.bold,
                  fontSize: 16,
                ),
              ),
              const SizedBox(height: 10),

              // Priority Selector Chips
              Row(
                children: _priorities.map((prio) {
                  final isSelected = _selectedPriority == prio;
                  final prioColor = _getPriorityColor(prio);
                  return Padding(
                    padding: const EdgeInsets.only(right: 8.0),
                    child: ChoiceChip(
                      label: Text(prio),
                      selected: isSelected,
                      selectedColor: prioColor.withValues(alpha: 0.2),
                      checkmarkColor: prioColor,
                      labelStyle: TextStyle(
                        color: isSelected ? prioColor : AppColors.textDark,
                        fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                      ),
                      backgroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(20),
                        side: BorderSide(
                          color: isSelected ? prioColor : Colors.transparent,
                          width: 1.5,
                        ),
                      ),
                      onSelected: (selected) {
                        if (selected) {
                          setState(() {
                            _selectedPriority = prio;
                          });
                        }
                      },
                    ),
                  );
                }).toList(),
              ),
              const SizedBox(height: 20),

              // Due Date Selector
              const Text(
                'Due Date',
                style: TextStyle(
                  color: AppColors.textDark,
                  fontWeight: FontWeight.bold,
                  fontSize: 16,
                ),
              ),
              const SizedBox(height: 10),
              InkWell(
                onTap: () => _selectDate(context),
                borderRadius: BorderRadius.circular(20),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          const Icon(Icons.calendar_today_rounded, color: AppColors.primary, size: 20),
                          const SizedBox(width: 12),
                          Text(
                            _selectedDueDate != null
                                ? DateFormat('MMM dd, yyyy').format(_selectedDueDate!)
                                : 'No Date Selected',
                            style: TextStyle(
                              color: _selectedDueDate != null ? AppColors.textDark : AppColors.textMuted,
                              fontSize: 15,
                              fontWeight: _selectedDueDate != null ? FontWeight.w500 : FontWeight.normal,
                            ),
                          ),
                        ],
                      ),
                      if (_selectedDueDate != null)
                        IconButton(
                          icon: const Icon(Icons.close, color: AppColors.priorityHigh, size: 18),
                          onPressed: () {
                            setState(() {
                              _selectedDueDate = null;
                            });
                          },
                        )
                      else
                        const Icon(Icons.arrow_drop_down, color: AppColors.textMuted),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 36),

              // Submit Button
              ElevatedButton(
                onPressed: _isSaving ? null : _save,
                child: _isSaving
                    ? const SizedBox(
                        height: 24,
                        width: 24,
                        child: CircularProgressIndicator(
                          color: Colors.white,
                          strokeWidth: 2.5,
                        ),
                      )
                    : Text(widget.task == null ? 'ADD TASK' : 'SAVE CHANGES'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
