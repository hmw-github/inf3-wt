<?php

class User {
    public function __construct(public string $name) {}
}

interface PaymentGateway {
    public function charge(float $amount): bool;
}

class CompleteTypeHintingDemo {
    
    // 1. SCALAR TYPES
    public function stringType(string $str): string {
        return $str;
    }
    
    public function intType(int $num): int {
        return $num;
    }
    
    public function floatType(float $num): float {
        return $num;
    }
    
    public function boolType(bool $flag): bool {
        return $flag;
    }
    
    // 2. COMPOUND TYPES
    public function arrayType(array $arr): array {
        return $arr;
    }
    
    public function objectType(object $obj): object {
        return $obj;
    }
    
    public function callableType(callable $fn): mixed {
        return $fn();
    }
    
    public function iterableType(iterable $items): void {
        foreach ($items as $item) {
            echo $item;
        }
    }
    
    // 3. SPECIAL TYPES
    public function mixedType(mixed $value): mixed {
        return $value;
    }
    
    public function voidType(): void {
        echo "No return value";
    }
    
    public function neverType(): never {
        exit(1);
    }
    
    public function nullType(): null {
        return null;
    }
    
    // 4. CLASS TYPES
    public function userType(User $user): User {
        return $user;
    }
    
    public function dateTimeType(\DateTime $date): \DateTime {
        return $date;
    }
    
    public function interfaceType(PaymentGateway $gateway): PaymentGateway {
        return $gateway;
    }
    
    // 5. SELF, PARENT, STATIC
    public function selfType(): self {
        return $this;
    }
    
    public static function staticType(): static {
        return new static();
    }
    
    // 6. NULLABLE TYPES
    public function nullableString(?string $str): ?string {
        return $str;
    }
    
    public function nullableInt(?int $num): ?int {
        return $num;
    }
    
    public function nullableUser(?User $user): ?User {
        return $user;
    }
    
    // 7. UNION TYPES (PHP 8+)
    public function stringOrInt(string|int $value): string|int {
        return $value;
    }
    
    public function intOrFloat(int|float $number): int|float {
        return $number;
    }
    
    public function arrayOrObject(array|object $data): array|object {
        return $data;
    }
    
    public function multipleTypes(int|float|string|null $value): int|float|string|null {
        return $value;
    }
    
    // 8. FALSE TYPE (PHP 8+)
    public function falseType(int $id): User|false {
        return false; // wenn nicht gefunden
    }
    
    // 9. TRUE TYPE (PHP 8.2+)
    public function trueType(): true {
        return true;
    }
    
    // 10. VARIADIC (beliebig viele Parameter)
    public function variadicType(string ...$names): array {
        foreach ($names as $name) {
            echo $name . "\n";
        }
        return $names;
    }
    
    // 11. OPTIONAL PARAMETERS
    public function optionalType(string $required, int $optional = 10): string {
        return $required . $optional;
    }
    
    // 12. REFERENCE PARAMETERS
    public function referenceType(int &$value): void {
        $value *= 2;
    }
}